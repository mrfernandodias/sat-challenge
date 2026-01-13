import DataTable from "datatables.net-bs5";
import "datatables.net-buttons-bs5";
import "datatables.net-buttons/js/buttons.colVis.mjs";
import "datatables.net-buttons/js/buttons.html5.mjs";
import "datatables.net-buttons/js/buttons.print.mjs";
import jszip from "jszip";
import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";
import Swal from "sweetalert2";
import "../bootstrap";
import { setupCepAutocomplete } from "../services/cep-service";
import { createMask, masks } from "../services/mask-service";

import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";
import "datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css";
import "../../css/datatables-custom.css";

pdfMake.vfs = pdfFonts.vfs;
window.JSZip = jszip;

let customersTable;
let editingCustomerId = null;
let cepAutocomplete = null;

const ptBrLanguage = {
    processing: "Processando...",
    search: "Pesquisar:",
    lengthMenu: "Mostrar _MENU_ registros",
    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
    infoEmpty: "Mostrando 0 a 0 de 0 registros",
    infoFiltered: "(filtrado de _MAX_ registros no total)",
    loadingRecords: "Carregando...",
    zeroRecords: "Nenhum registro encontrado",
    emptyTable: "Nenhum dado disponível na tabela",
    paginate: {
        first: "Primeiro",
        previous: "Anterior",
        next: "Próximo",
        last: "Último",
    },
    aria: {
        sortAscending: ": ativar para ordenar coluna ascendente",
        sortDescending: ": ativar para ordenar coluna descendente",
    },
    buttons: {
        copy: "Copiar",
        copyTitle: "Copiado para a área de transferência",
        copySuccess: { _: "%d linhas copiadas", 1: "1 linha copiada" },
        print: "Imprimir",
        colvis: "Colunas",
        excel: "Excel",
        pdf: "PDF",
    },
};

document.addEventListener("DOMContentLoaded", function () {
    initDataTable();
    initForm();
    initActions();
    initCepAutocomplete();
    initMasks();
});

function initMasks() {
    const fields = [
        { id: "cep", mask: masks.cep },
        { id: "cpf", mask: masks.cpf },
        { id: "phone", mask: masks.phone },
    ];

    fields.forEach(({ id, mask }) => {
        const input = document.getElementById(id);
        if (input) createMask(input, mask);
    });
}

function initDataTable() {
    customersTable = new DataTable("#customers-table", {
        ajax: { url: "/customers/data", dataSrc: "data" },
        language: ptBrLanguage,
        pagingType: "simple_numbers",
        pageLength: 10,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Todos"],
        ],
        order: [[0, "desc"]],
        columns: [
            { data: "id" },
            { data: "name" },
            { data: "phone" },
            { data: "cpf" },
            { data: "email" },
            { data: "city" },
            { data: "state" },
            {
                data: "created_at",
                render: function (data) {
                    if (!data) return "";
                    const date = new Date(data);
                    return date.toLocaleDateString("pt-BR", {
                        day: "2-digit",
                        month: "2-digit",
                        year: "numeric",
                        hour: "2-digit",
                        minute: "2-digit",
                    });
                },
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: "dt-actions",
                render: (data, type, row) => `
                    <button type="button" class="dt-btn-action btn-edit" data-id="${row.id}" title="Editar">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button type="button" class="dt-btn-action btn-delete" data-id="${row.id}" title="Excluir">
                        <i class="bi bi-trash"></i>
                    </button>
                `,
            },
        ],
        dom:
            '<"row align-items-center mb-3"<"col-md-6 d-flex align-items-center gap-3"l B><"col-md-6"f>>' +
            '<"row"<"col-12"t>>' +
            '<"row mt-3 align-items-center"<"col-md-5"i><"col-md-7 d-flex justify-content-end"p>>',
        buttons: {
            dom: {
                container: { className: "btn-group btn-group-sm" },
                button: { className: "btn btn-outline-secondary" },
            },
            buttons: [
                {
                    extend: "collection",
                    text: '<i class="bi bi-download me-1"></i> Exportar',
                    buttons: [
                        {
                            extend: "copy",
                            text: '<i class="bi bi-clipboard me-2"></i> Copiar',
                            className: "dropdown-item",
                            exportOptions: { columns: ":not(:last-child)" },
                        },
                        {
                            extend: "csv",
                            text: '<i class="bi bi-filetype-csv me-2"></i> CSV',
                            className: "dropdown-item",
                            exportOptions: { columns: ":not(:last-child)" },
                        },
                        {
                            extend: "excel",
                            text: '<i class="bi bi-file-earmark-excel me-2"></i> Excel',
                            className: "dropdown-item",
                            exportOptions: { columns: ":not(:last-child)" },
                        },
                        {
                            extend: "pdf",
                            text: '<i class="bi bi-file-earmark-pdf me-2"></i> PDF',
                            className: "dropdown-item",
                            exportOptions: { columns: ":not(:last-child)" },
                        },
                        {
                            extend: "print",
                            text: '<i class="bi bi-printer me-2"></i> Imprimir',
                            className: "dropdown-item",
                            exportOptions: { columns: ":not(:last-child)" },
                        },
                    ],
                },
                {
                    extend: "colvis",
                    text: '<i class="bi bi-layout-three-columns me-1"></i> Colunas',
                },
            ],
        },
    });
}

function initActions() {
    document
        .querySelector("#customers-table")
        .addEventListener("click", function (e) {
            const editBtn = e.target.closest(".btn-edit");
            const deleteBtn = e.target.closest(".btn-delete");

            if (editBtn) handleEdit(editBtn.dataset.id);
            if (deleteBtn) handleDelete(deleteBtn.dataset.id);
        });

    const offcanvasElement = document.getElementById("offcanvasCustomer");
    offcanvasElement?.addEventListener("hidden.bs.offcanvas", resetForm);
}

async function handleEdit(id) {
    const rowData = customersTable
        .rows()
        .data()
        .toArray()
        .find((row) => row.id == id);

    if (!rowData) {
        Swal.fire({
            icon: "error",
            title: "Erro!",
            text: "Cliente não encontrado.",
        });
        return;
    }

    editingCustomerId = id;
    fillForm(rowData);
    document.getElementById("offcanvasCustomerLabel").textContent =
        "Editar cliente";

    const offcanvas = new bootstrap.Offcanvas(
        document.getElementById("offcanvasCustomer")
    );
    offcanvas.show();
}

function fillForm(data) {
    const fields = [
        "name",
        "phone",
        "cpf",
        "email",
        "cep",
        "street",
        "neighborhood",
        "number",
        "complement",
        "city",
        "state",
    ];
    fields.forEach((field) => {
        document.getElementById(field).value = data[field] || "";
    });
}

function resetForm() {
    editingCustomerId = null;
    document.getElementById("customerForm").reset();
    document.getElementById("offcanvasCustomerLabel").textContent =
        "Criar novo cliente";
    clearValidationErrors();
    cepAutocomplete?.clearFields();
}

function initCepAutocomplete() {
    const cepInput = document.getElementById("cep");
    if (!cepInput) return;

    const fieldMap = {
        street: document.getElementById("street"),
        neighborhood: document.getElementById("neighborhood"),
        city: document.getElementById("city"),
        state: document.getElementById("state"),
    };

    cepAutocomplete = setupCepAutocomplete(cepInput, fieldMap, {
        onLoading: (loading) => {
            cepInput.classList.toggle("loading", loading);
        },
        onError: (message) => {
            Swal.fire({
                icon: "warning",
                title: "Atenção",
                text: message,
                toast: true,
                position: "top-end",
                timer: 3000,
                showConfirmButton: false,
            });
        },
    });
}

async function handleDelete(id) {
    const result = await Swal.fire({
        title: "Tem certeza?",
        text: "Esta ação não poderá ser desfeita!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Sim, excluir!",
        cancelButtonText: "Cancelar",
    });

    if (!result.isConfirmed) return;

    try {
        await axios.delete(`/customers/${id}`);
        customersTable.ajax.reload();
        Swal.fire({
            icon: "success",
            title: "Excluído!",
            text: "Cliente excluído com sucesso.",
            timer: 2000,
            showConfirmButton: false,
        });
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Erro!",
            text: error.response?.data?.message || "Erro ao excluir cliente.",
        });
    }
}

function initForm() {
    const form = document.getElementById("customerForm");
    if (!form) return;

    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn?.innerHTML;

    const setLoading = (loading) => {
        if (!submitBtn) return;
        submitBtn.disabled = loading;
        submitBtn.innerHTML = loading
            ? '<span class="spinner-border spinner-border-sm me-2"></span>Salvando...'
            : originalBtnText;
    };

    form.addEventListener("submit", async function (e) {
        e.preventDefault();
        clearValidationErrors();
        setLoading(true);

        const formData = getFormData();

        try {
            let response, successMessage;

            if (editingCustomerId) {
                response = await axios.put(
                    `/customers/${editingCustomerId}`,
                    formData
                );
                successMessage = "Cliente atualizado com sucesso!";
            } else {
                response = await axios.post("/customers", formData);
                successMessage = "Cliente criado com sucesso!";
            }

            bootstrap.Offcanvas.getInstance(
                document.getElementById("offcanvasCustomer")
            )?.hide();
            customersTable.ajax.reload(null, false);
            Swal.fire({
                icon: "success",
                title: "Sucesso!",
                text: response.data.message || successMessage,
                timer: 2000,
                showConfirmButton: false,
            });
        } catch (error) {
            handleError(error);
        } finally {
            setLoading(false);
        }
    });
}

function getFormData() {
    const fields = [
        "name",
        "phone",
        "cpf",
        "email",
        "cep",
        "street",
        "neighborhood",
        "number",
        "complement",
        "city",
        "state",
    ];
    const data = {};
    fields.forEach((field) => {
        data[field] = document.getElementById(field).value;
    });
    return data;
}

function handleError(error) {
    if (error.response?.status === 422) {
        showValidationErrors(error.response.data.errors);
        Swal.fire({
            icon: "error",
            title: "Erro de validação",
            text: "Por favor, corrija os campos destacados.",
        });
    } else {
        Swal.fire({
            icon: "error",
            title: "Erro!",
            text:
                error.response?.data?.message ||
                "Erro ao processar requisição.",
        });
    }
}

function showValidationErrors(errors) {
    Object.keys(errors).forEach((field) => {
        const input = document.getElementById(field);
        if (!input) return;

        input.classList.add("is-invalid");
        const feedback = document.createElement("div");
        feedback.className = "invalid-feedback";
        feedback.textContent = errors[field][0];
        input.parentNode.appendChild(feedback);
    });
}

function clearValidationErrors() {
    document
        .querySelectorAll(".is-invalid")
        .forEach((el) => el.classList.remove("is-invalid"));
    document.querySelectorAll(".invalid-feedback").forEach((el) => el.remove());
}
