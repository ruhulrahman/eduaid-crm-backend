<template>
  <b-overlay :show="loading">
    <div class="formBoder">
    <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
      <b-form @submit.prevent="handleSubmit(submitData)" @reset.prevent="reset" autocomplete="off">
        <b-row>
        <b-col lg="6" md="6" sm="12" xs="12">
          <ValidationProvider name="Parent" vid="parent_id" rules="" v-slot="{ errors }">
              <b-form-group
                class="row"
                label-cols-sm="12"
                label-for="parent_id"
              >
              <template v-slot:label>
                Parent
              </template>
              <treeselect
                id="parent_id"
                :options="parentList"
                placeholder="Select Parent"
                v-model="form.parent_id"
                :state="errors[0] ? false : (valid ? true : null)"
                />
              <template v-slot:first>
                <b-form-select-option :id=0>Select</b-form-select-option>
              </template>
            <div class="d-block invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="6" md="6" sm="12" xs="12">
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
        </b-row>
        <b-row>
        <b-col lg="12" md="12" sm="12" xs="12">
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
              <!-- <span>Month</span> -->
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
// import the component
import Treeselect from '@riophae/vue-treeselect'
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

export default {
  props: ['editItem', 'parentList'],
  components: {
    Treeselect
  },
  data () {
    return {
      SaveButton: this.editItem ? 'Update' : 'Save',
      form: {
        name: '',
        short_name: '',
        parent_id: null,
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
    }
  },
  methods: {
    async submitData () {
      this.loading = true
      let result = ''
      if (this.form.id) {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_language_test_data', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_language_test_data', this.form)
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
