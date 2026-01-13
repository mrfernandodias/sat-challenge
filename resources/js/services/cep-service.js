const VIACEP_URL = "https://viacep.com.br/ws";

export function cleanCep(cep) {
    return cep.replace(/\D/g, "");
}

export function isValidCepFormat(cep) {
    const cleaned = cleanCep(cep);
    return /^\d{8}$/.test(cleaned);
}

export async function fetchAddress(cep) {
    const cleaned = cleanCep(cep);

    if (!isValidCepFormat(cleaned)) {
        throw new Error("CEP inválido. Deve conter 8 dígitos.");
    }

    const response = await fetch(`${VIACEP_URL}/${cleaned}/json/`);

    if (!response.ok) {
        throw new Error("Erro ao consultar CEP.");
    }

    const data = await response.json();

    if (data.erro) {
        throw new Error("CEP não encontrado.");
    }

    return {
        cep: data.cep,
        street: data.logradouro,
        neighborhood: data.bairro,
        city: data.localidade,
        state: data.uf,
        complement: data.complemento || null,
    };
}

export function setupCepAutocomplete(cepInput, fieldMap, options = {}) {
    const {
        onLoading = () => {},
        onSuccess = () => {},
        onError = () => {},
        disableFieldsOnSuccess = true,
    } = options;

    const filledByApi = new Set();

    const fillFields = (address) => {
        filledByApi.clear();

        Object.entries(fieldMap).forEach(([key, element]) => {
            if (!element || !address[key]) return;

            element.value = address[key];
            filledByApi.add(key);

            if (disableFieldsOnSuccess && key !== "complement") {
                element.readOnly = true;
                element.classList.add("bg-light");
            }
        });
    };

    const clearFields = () => {
        Object.entries(fieldMap).forEach(([key, element]) => {
            if (!element) return;

            if (filledByApi.has(key)) {
                element.value = "";
            }

            element.readOnly = false;
            element.classList.remove("bg-light");
        });

        filledByApi.clear();
    };

    const handleCepBlur = async () => {
        const cep = cepInput.value;

        if (!cep || !isValidCepFormat(cep)) {
            clearFields();
            return;
        }

        onLoading(true);

        try {
            const address = await fetchAddress(cep);
            fillFields(address);
            onSuccess(address);
        } catch (error) {
            clearFields();
            onError(error.message);
        } finally {
            onLoading(false);
        }
    };

    cepInput.addEventListener("blur", handleCepBlur);

    return {
        destroy: () => cepInput.removeEventListener("blur", handleCepBlur),
        clearFields,
        refetch: handleCepBlur,
    };
}
