<template>
  <b-container>
      <b-row>
          <b-col cols="6" offset="3">
              <register-form></register-form>
          </b-col>
      </b-row>
  </b-container>
</template>

<script>
import RegisterForm from '../components/RegisterForm';

export default {
    components: {
        RegisterForm,
    },
}
</script>

<style>

</style>