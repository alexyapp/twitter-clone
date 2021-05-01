export const formValidationMixin = {
    computed: {
        isFormValid() {
            for (let key in this.form) {
                if (!this.form[key].valid) {
                    return false;
                }
            }
            return true;
        }
    },  

    methods: {
        isValidEmail(value) {
            return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value);
        },

        isEqual(value, valueToCheck) {
            return value == valueToCheck;
        },

        minLength(value, length) {
            return value.length >= length;
        },

        maxLength(value, length) {
            return value.length <= length;
        },

        setValidity(value, field) {
            this.form[field].valid = value;
        },

        setErrorMessage(message, field) {
            this.form[field].errorMessage = message;
        },
    },
};