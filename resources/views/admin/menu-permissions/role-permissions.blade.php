@extends('layouts.app')

@section('styles')
<style>
    .statbox {
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }
    .widget-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e4e6eb;
        background: linear-gradient(135deg, #f5f7fa 0%, #f9f9fc 100%);
    }
    .widget-header h4 {
        color: #212529;
        font-weight: 600;
        margin: 0;
    }
    .widget-content {
        padding: 1.5rem;
    }
    .form-check-label {
        color: #212529;
        margin-bottom: 0;
        cursor: pointer;
        font-weight: 500;
    }
    .form-check {
        padding: 0.5rem 0;
    }
    .seperator-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #dee2e6;
    }
    .seperator-header h2 {
        color: #212529;
        font-weight: 600;
    }
    .layout-spacing {
        margin-bottom: 2rem;
    }
    .space-y-2 > div {
        margin-bottom: 0.5rem;
    }
    .space-y-2 > div:last-child {
        margin-bottom: 0;
    }
</style>
@endsection

@section('content')

<div class="row layout-top-spacing">
    <div class="col-12">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="page-title">Permisos: <span class="text-primary">{{ $role->nombre }}</span></h2>
            </div>
            <a href="{{ route('menu-permissions.index') }}" class="btn btn-secondary btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Volver
            </a>
        </div>
    </div>
</div>

<div class="row layout-spacing">
    <div class="col-12">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <h4>Asignar Menús y Permisos a {{ $role->nombre }}</h4>
            </div>
            <div class="widget-content">
                <form action="{{ route('menu-permissions.update-role-permissions', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    @php
                        $mainMenus = $menus->where('nivel', 0);
                    @endphp

                    @forelse($mainMenus as $group)
                        <div class="mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                            <div class="form-check mb-3">
                                <input 
                                    type="checkbox" 
                                    class="form-check-input group-checkbox" 
                                    data-group="{{ $group->id }}"
                                    id="menu_{{ $group->id }}" 
                                    name="permissions[]" 
                                    value="{{ $group->id }}"
                                    {{ in_array($group->id, $permissions) ? 'checked' : '' }}
                                >
                                <label class="form-check-label text-primary fw-bold" for="menu_{{ $group->id }}">
                                    {{ $group->nombre }}
                                </label>
                            </div>

                            @php
                                $subItems = $menus->where('id_padre', $group->id);
                            @endphp

                            @if($subItems->count() > 0)
                                <div class="ms-4 ps-3 border-start space-y-2">
                                    @foreach($subItems as $item)
                                        <div class="form-check">
                                            <input 
                                                type="checkbox" 
                                                class="form-check-input item-checkbox" 
                                                data-group="{{ $group->id }}"
                                                id="menu_{{ $item->id }}" 
                                                name="permissions[]" 
                                                value="{{ $item->id }}"
                                                {{ in_array($item->id, $permissions) ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label" for="menu_{{ $item->id }}">
                                                {{ $item->nombre }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted text-center py-4">No hay menús disponibles</p>
                    @endforelse

                    <div class="text-end mt-4 pt-4 border-top">
                        <a href="{{ route('menu-permissions.index') }}" class="btn btn-light">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 4 20 10 14 10"></polyline><polyline points="4 20 4 14 10 14"></polyline><path d="M4 4v7a7 7 0 0 0 12 0V4m0 16h7a7 7 0 0 0-7-7"></path></svg>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.group-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const groupId = this.dataset.group;
            const isChecked = this.checked;
            document.querySelectorAll(`.item-checkbox[data-group="${groupId}"]`).forEach(item => {
                item.checked = isChecked;
            });
        });
    });

    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const groupId = this.dataset.group;
            const groupCheckbox = document.querySelector(`.group-checkbox[data-group="${groupId}"]`);
            const allItems = document.querySelectorAll(`.item-checkbox[data-group="${groupId}"]`);
            const checkedItems = document.querySelectorAll(`.item-checkbox[data-group="${groupId}"]:checked`);
            
            if (allItems.length === checkedItems.length) {
                groupCheckbox.checked = true;
            } else {
                groupCheckbox.checked = false;
            }
        });
    });
});
</script>
@endsection
