<template>
  <b-overlay :show="loading">
    <div class="formBoder">
    <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
      <b-form @submit.prevent="handleSubmit(submitData)" @reset.prevent="reset" autocomplete="off">
        <b-row>
        <b-col lg="12" md="12" sm="12" xs="12">
          <ValidationProvider name="Name" vid="name" rules="required" v-slot="{ errors }">
            <b-form-group
              class="row"
              label-cols-sm="12"
              label-for="name"
            >
            <template v-slot:label>
              Name <span class="text-danger">*</span>
            </template>
              <b-form-input
                id="name"
                v-model="form.name"
                placeholder="Enter Name"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
              <div class="invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="6" md="6" sm="12" xs="12">
          <ValidationProvider name="Short Name" vid="short_name" rules="required" v-slot="{ errors }">
            <b-form-group
              class="row"
              label-cols-sm="12"
              label-for="short_name"
            >
            <template v-slot:label>
              Short Name <span class="text-danger">*</span>
            </template>
            <b-input-group>
              <b-form-input
                id="short_name"
                v-model="form.short_name"
                placeholder="Enter Short Name"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
            </b-input-group>
              <div class="d-block invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="6" md="6" sm="12" xs="12">
          <ValidationProvider name="Serial" vid="serial" rules="required" v-slot="{ errors }">
            <b-form-group
              class="row"
              label-cols-sm="12"
              label-for="serial"
            >
            <template v-slot:label>
              Serial <span class="text-danger">*</span>
            </template>
            <b-input-group>
              <b-form-input
                id="serial"
                type="number"
                v-model="form.serial"
                placeholder="Enter Serial"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
            </b-input-group>
              <div class="d-block invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
       </b-row>
      <div class="row mt-3">
        <div class="col-sm-3"></div>
        <div class="col text-right">
            <b-button type="submit" variant="primary" class="mr-2">{{ SaveButton }}</b-button>
            &nbsp;
            <b-button variant="danger" class="mr-1" @click="$bvModal.hide('modal-1')">Cancel</b-button>
        </div>
      </div>
     </b-form>
    </ValidationObserver>
    </div>
  </b-overlay>
</template>
<script>
import RestApi, { baseURL } from '@/config'

export default {
  props: ['editItem'],
  components: {
  },
  data () {
    return {
      SaveButton: this.editItem ? 'Update' : 'Save',
      form: {
        name: '',
        short_name: '',
        serial: '',
        active: 1
      },
      errors: [],
      valid: null,
      loading: false
    }
  },
  created () {
    if (this.editItem) {
      this.form = JSON.parse(this.editItem)
    } else {
      this.getCourseLevelLastSerial()
    }
  },
  methods: {
    async submitData () {
      this.loading = true
      let result = ''
      if (this.form.id) {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_course_level_data', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_course_level_data', this.form)
      }
      this.loading = false
      if (result.success) {
        this.$emit('reloadList', true)
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.$bvModal.hide('modal-1')
        this.getCommonDropdownList()
      } else {
        this.$refs.form.setErrors(result.errors)
      }
    },
    async getCourseLevelLastSerial () {
      const result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_course_level_last_serial')
      if (result.success) {
        this.form.serial = result.data
      }
    }
  }
}
</script>
<style>
 .formBoder {
    border: 1px solid;
    margin: 5px;
    padding: 35px;
    font-size: 13px
 }
 .input-group-text {
   height: 30.5px!important;
 }
</style>
