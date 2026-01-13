export function applyMask(value, pattern) {
    const digits = value.replace(/\D/g, "");
    let result = "";
    let digitIndex = 0;

    for (let i = 0; i < pattern.length && digitIndex < digits.length; i++) {
        if (pattern[i] === "9") {
            result += digits[digitIndex];
            digitIndex++;
        } else {
            result += pattern[i];
            if (pattern[i] === digits[digitIndex]) {
                digitIndex++;
            }
        }
    }

    return result;
}

export function createMask(input, pattern) {
    const maxDigits =
        pattern.replace(/\D/g, "").length || pattern.split("9").length - 1;

    const handleInput = (e) => {
        const cursorPos = e.target.selectionStart;
        const oldValue = e.target.value;
        const newValue = applyMask(e.target.value, pattern);

        e.target.value = newValue;

        if (newValue.length > oldValue.length && cursorPos < newValue.length) {
            const diff = newValue.length - oldValue.length;
            e.target.setSelectionRange(cursorPos + diff, cursorPos + diff);
        }
    };

    const handleKeydown = (e) => {
        const isNumber = /^\d$/.test(e.key);
        const isControl = [
            "Backspace",
            "Delete",
            "Tab",
            "ArrowLeft",
            "ArrowRight",
            "Home",
            "End",
        ].includes(e.key);
        const isModifier = e.ctrlKey || e.metaKey;

        if (!isNumber && !isControl && !isModifier) {
            e.preventDefault();
        }
    };

    input.addEventListener("input", handleInput);
    input.addEventListener("keydown", handleKeydown);

    if (input.value) {
        input.value = applyMask(input.value, pattern);
    }

    return {
        destroy: () => {
            input.removeEventListener("input", handleInput);
            input.removeEventListener("keydown", handleKeydown);
        },
        update: (value) => {
            input.value = applyMask(value, pattern);
        },
    };
}

export const masks = {
    cep: "99999-999",
    cpf: "999.999.999-99",
    phone: "(99) 99999-9999",
};
