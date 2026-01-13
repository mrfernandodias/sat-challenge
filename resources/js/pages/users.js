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

import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";
import "datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css";
import "../../css/datatables-custom.css";

pdfMake.vfs = pdfFonts.vfs;
window.JSZip = jszip;

let usersTable;
let editingUserId = null;

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
});

function initDataTable() {
    usersTable = new DataTable("#users-table", {
        ajax: { url: "/users/data", dataSrc: "data" },
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
            { data: "email" },
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
        .querySelector("#users-table")
        .addEventListener("click", function (e) {
            const editBtn = e.target.closest(".btn-edit");
            const deleteBtn = e.target.closest(".btn-delete");

            if (editBtn) handleEdit(editBtn.dataset.id);
            if (deleteBtn) handleDelete(deleteBtn.dataset.id);
        });

    const offcanvasElement = document.getElementById("offcanvasUser");
    offcanvasElement?.addEventListener("hidden.bs.offcanvas", resetForm);
}

async function handleEdit(id) {
    const rowData = usersTable
        .rows()
        .data()
        .toArray()
        .find((row) => row.id == id);

    if (!rowData) {
        Swal.fire({
            icon: "error",
            title: "Erro!",
            text: "Usuário não encontrado.",
        });
        return;
    }

    editingUserId = id;
    fillForm(rowData);
    document.getElementById("offcanvasUserLabel").textContent =
        "Editar usuário";
    document.querySelector(".password-hint").textContent =
        "Deixe em branco para manter a senha atual";

    const offcanvas = new bootstrap.Offcanvas(
        document.getElementById("offcanvasUser")
    );
    offcanvas.show();
}

function fillForm(data) {
    document.getElementById("name").value = data.name || "";
    document.getElementById("email").value = data.email || "";
    document.getElementById("password").value = "";
    document.getElementById("password_confirmation").value = "";
    document.getElementById("password").removeAttribute("required");
    document
        .getElementById("password_confirmation")
        .removeAttribute("required");
}

function resetForm() {
    editingUserId = null;
    document.getElementById("userForm").reset();
    document.getElementById("offcanvasUserLabel").textContent =
        "Criar novo usuário";
    document.querySelector(".password-hint").textContent =
        "Obrigatório para novos usuários";
    document.getElementById("password").setAttribute("required", "required");
    document
        .getElementById("password_confirmation")
        .setAttribute("required", "required");
    clearValidationErrors();
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
        await axios.delete(`/users/${id}`);
        usersTable.ajax.reload();
        Swal.fire({
            icon: "success",
            title: "Excluído!",
            text: "Usuário excluído com sucesso.",
            timer: 2000,
            showConfirmButton: false,
        });
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Erro!",
            text: error.response?.data?.message || "Erro ao excluir usuário.",
        });
    }
}

function initForm() {
    const form = document.getElementById("userForm");
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

            if (editingUserId) {
                response = await axios.put(`/users/${editingUserId}`, formData);
                successMessage = "Usuário atualizado com sucesso!";
            } else {
                response = await axios.post("/users", formData);
                successMessage = "Usuário criado com sucesso!";
            }

            bootstrap.Offcanvas.getInstance(
                document.getElementById("offcanvasUser")
            )?.hide();
            usersTable.ajax.reload(null, false);
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
    const data = {
        name: document.getElementById("name").value,
        email: document.getElementById("email").value,
    };

    const password = document.getElementById("password").value;
    const passwordConfirmation = document.getElementById(
        "password_confirmation"
    ).value;

    if (password) {
        data.password = password;
        data.password_confirmation = passwordConfirmation;
    }

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
