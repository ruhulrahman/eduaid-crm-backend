<template>
  <b-overlay :show="loading">
    <div class="formBoder">
    <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
      <b-form @submit.prevent="handleSubmit(submitData)" @reset.prevent="reset" autocomplete="off">
        <b-row>
        <b-col lg="6" md="6" sm="12" xs="12">
          <ValidationProvider name="Media Name" vid="media_name" rules="required" v-slot="{ errors }">
            <b-form-group
              class="row"
              label-cols-sm="12"
              label-for="media_name"
            >
            <template v-slot:label>
              Media Name <span class="text-danger">*</span>
            </template>
              <b-form-input
                id="media_name"
                v-model="form.media_name"
                placeholder="Enter Media Name"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
              <div class="invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="6" md="6" sm="12" xs="12">
          <ValidationProvider name="Icon Name" vid="icon_name" rules="required" v-slot="{ errors }">
            <b-form-group
              class="row"
              label-cols-sm="12"
              label-for="icon_name"
            >
            <template v-slot:label>
              Icon Name <span class="text-danger">*</span>
            </template>
            <b-input-group>
              <b-form-input
                id="icon_name"
                disabled
                v-model="form.icon_name"
                placeholder="Enter Icon Name"
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
          <ValidationProvider name="Icon Value" vid="icon_value" rules="required" v-slot="{ errors }">
            <b-form-group
              class="row"
              label-cols-sm="12"
              label-for="icon_value"
            >
            <template v-slot:label>
              Icon Value <span class="text-danger">*</span>
              <i v-if="form.icon_name == 'remix'" :style="`color: ${form.icon_color}`" class="font-size-18 mr-1" :class="form.icon_value"></i>
            </template>
            <b-input-group>
              <b-form-input
                id="icon_value"
                v-model="form.icon_value"
                placeholder="ri-google-fill"
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
          <ValidationProvider name="Icon Color" vid="icon_color" rules="required" v-slot="{ errors }">
            <b-form-group
              class="row"
              label-cols-sm="12"
              label-for="icon_color"
            >
            <template v-slot:label>
              Icon Color <span class="text-danger">*</span>
            </template>
            <b-input-group>
              <b-form-input
                id="icon_color"
                type="color"
                v-model="form.icon_color"
                placeholder="Enter Icon Color"
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
        icon_name: 'remix',
        icon_value: '',
        icon_color: '#000000',
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
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_social_data', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_social_data', this.form)
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
