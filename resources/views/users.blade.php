@extends('layouts.app')

@section('title', 'Usuários')
@section('page-title', 'Usuários')
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Usuários</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Usuários</h3>
                    <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasUser">
                        <i class="bi bi-plus-lg"></i> Novo Usuário
                    </button>
                </div>
                <div class="card-body">
                    <table id="users-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Criado em</th>
                                <th width="100">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" style="--bs-offcanvas-width: 450px;" tabindex="-1" id="offcanvasUser"
        aria-labelledby="offcanvasUserLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="offcanvasUserLabel">Criar novo usuário</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
        </div>
        <form id="userForm" class="d-flex flex-column overflow-hidden" style="height: calc(100% - 56px);">
            <div class="offcanvas-body overflow-auto">
                <div class="form-group row mb-3">
                    <label for="name" class="col-sm-4 col-form-label">Nome</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="name" placeholder="Ex: João da Silva" required>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="email" class="col-sm-4 col-form-label">E-mail</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="email" placeholder="email@example.com" required>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="password" class="col-sm-4 col-form-label">Senha</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" id="password" placeholder="Mínimo 6 caracteres">
                        <small class="text-muted password-hint">Obrigatório para novos usuários</small>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="password_confirmation" class="col-sm-4 col-form-label">Confirmar Senha</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" id="password_confirmation" placeholder="Repita a senha">
                    </div>
                </div>
            </div>
            <div class="border-top p-3">
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Salvar
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/pages/users.js')
@endpush
