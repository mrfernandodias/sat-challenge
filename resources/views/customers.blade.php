@extends('layouts.app')

@section('title', 'Clientes')
@section('page-title', 'Clientes')
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Clientes</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Lista de Clientes</h3>
                    <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasCustomer">
                        <i class="bi bi-plus-lg"></i> Novo Cliente
                    </button>
                </div>
                <div class="card-body">
                    <table id="customers-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Telefone</th>
                                <th>CPF</th>
                                <th>E-mail</th>
                                <th>Cidade</th>
                                <th>UF</th>
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

    <div class="offcanvas offcanvas-end" style="--bs-offcanvas-width: 550px;" tabindex="-1" id="offcanvasCustomer"
        aria-labelledby="offcanvasCustomerLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="offcanvasCustomerLabel">Criar novo cliente</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
        </div>
        <form id="customerForm" class="d-flex flex-column overflow-hidden" style="height: calc(100% - 56px);">
            <div class="offcanvas-body overflow-auto">
                <div class="border-bottom mb-3">
                    <div>
                        <span class="small text-uppercase text-muted fw-semibold d-block mb-4">DADOS PESSOAIS</span>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="name" class="col-sm-3 col-form-label">Nome completo</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" placeholder="Ex: João da Silva"
                                required>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="phone" class="col-sm-3 col-form-label">Telefone</label>
                        <div class="col-sm-9">
                            <input type="tel" class="form-control" id="phone" placeholder="(99) 99999-9999"
                                required>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="cpf" class="col-sm-3 col-form-label">CPF</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="cpf" placeholder="123.456.789-00" required>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="email" class="col-sm-3 col-form-label">E-mail</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="email" placeholder="email@example.com"
                                required>
                        </div>
                    </div>
                </div>

                <div class="">
                    <div>
                        <span class="small text-uppercase text-muted fw-semibold d-block mb-4">ENDEREÇO</span>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="cep" class="col-sm-3 col-form-label">CEP</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="cep" placeholder="Ex: 12345-678" required>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="street" class="col-sm-3 col-form-label">Rua</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="street" placeholder="Ex: Rua das Flores"
                                required>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="neighborhood" class="col-sm-3 col-form-label">Bairro</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="neighborhood" placeholder="Ex: Centro" required>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="number" class="col-sm-3 col-form-label">Número</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="number" placeholder="Ex: 123" required>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="complement" class="col-sm-3 col-form-label">Complemento</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="complement"
                                placeholder="Ex: Apt 101, Bloco B">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="city" class="col-sm-3 col-form-label">Cidade
                        </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="city" placeholder="Ex: São Paulo"
                                required>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="state" class="col-sm-3 col-form-label">Estado
                        </label>
                        <div class="col-sm-9">

                            <select name="state" id="state" class="form-control" required>
                                <option value="">Selecione o estado</option>
                                <option value="AC">Acre</option>
                                <option value="AL">Alagoas</option>
                                <option value="AP">Amapá</option>
                                <option value="AM">Amazonas</option>
                                <option value="BA">Bahia</option>
                                <option value="CE">Ceará</option>
                                <option value="DF">Distrito Federal</option>
                                <option value="ES">Espírito Santo</option>
                                <option value="GO">Goiás</option>
                                <option value="MA">Maranhão</option>
                                <option value="MT">Mato Grosso</option>
                                <option value="MS">Mato Grosso do Sul</option>
                                <option value="MG">Minas Gerais</option>
                                <option value="PA">Pará</option>
                                <option value="PB">Paraíba</option>
                                <option value="PR">Paraná</option>
                                <option value="PE">Pernambuco</option>
                                <option value="PI">Piauí</option>
                                <option value="RJ">Rio de Janeiro</option>
                                <option value="RN">Rio Grande do Norte</option>
                                <option value="RS">Rio Grande do Sul</option>
                                <option value="RO">Rondônia</option>
                                <option value="RR">Roraima</option>
                                <option value="SC">Santa Catarina</option>
                                <option value="SP">São Paulo</option>
                                <option value="SE">Sergipe</option>
                                <option value="TO">Tocantins</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
            <div class="border-top p-3">
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="offcanvas">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Salvar
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/pages/customers.js')
@endpush
