<template>
  <b-overlay :show="loading">
  <div class="formBoder">
    <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
      <b-form @submit.prevent="handleSubmit(updateData)" @reset.prevent="reset" autocomplete="off">
      <b-row>
        <b-col lg="6" md="6" sm="6" xs="12">
          <ValidationProvider name="Photo" vid="Photo">
            <b-form-group
              label-for="photo"
              slot-scope="{ valid, errors }"
            >
            <template v-slot:label>
              <img v-if="attachtedFileUrl" :src="attachtedFileUrl" class="mr-2" width="70" alt="Profile Image">
              <img v-else src="../../../../assets/images/man.png" class="mr-2" width="70" alt="Profile Image">
            </template>

            <!-- <input type="file" v-on:change="uploadFile" ref="file"/> -->

            <b-form-file
            class="mt-3"
              id="photo"
              v-on:change="onFileChange"
              accept="image/*"
              v-model="form.photo"
              ref="file"
              :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-file>
            <div class="invalid-feedback">
              {{ errors[0] }}
            </div>
            <!-- <b-img style="margin-bottom: 10px" width='100px' v-if="form.id && form.photo" :src="baseURL + form.photo" fluid alt="Profile Photo"></b-img> -->
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="6" md="6" sm="6" xs="12">
          <div class="profile">
            <h5>Name:
              <ValidationProvider name="Name" vid="name" rules="required" v-slot="{ errors }">
                <b-form-input
                  id="name"
                  v-model="form.name"
                  :state="errors[0] ? false : (valid ? true : null)"
                ></b-form-input>
                <div class="invalid-feedback">
                  {{ errors[0] }}
                </div>
            </ValidationProvider>
            </h5>
            <!-- <h5>Email:
              <ValidationProvider name="Email" vid="email" rules="required" v-slot="{ errors }">
              <b-form-input
              id="email"
                type="email"
                v-model="form.email"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
              <div class="invalid-feedback">
                  {{ errors[0] }}
                </div>
            </ValidationProvider>
            </h5> -->
            <h5>Phone:
              <ValidationProvider name="Phone" vid="phone" rules="required" v-slot="{ errors }">
              <b-form-input
               id="phone"
                v-model="form.phone"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
              <div class="invalid-feedback">
                {{ errors[0] }}
              </div>
            </ValidationProvider>
            </h5>
          </div>
        </b-col>
      </b-row>
      <div class="row mt-3">
        <div class="col-sm-3"></div>
        <div class="col text-right">
          <b-button type="submit" variant="primary" class="mr-2">Update</b-button>
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
  data () {
    return {
      form: {
        name: '',
        email: '',
        phone: '',
        photo: null
      },
      attachtedFileUrl: '',
      attachtedFile: '',
      upload_photo: [],
      errors: [],
      valid: null,
      loading: false,
      fileValidationMsz: '',
      value: null
    }
  },
  created () {
    this.getProfileList()
  },
  mounted () {
  },
  methods: {
    onFileChange (event) {
      const inputFile = event.target.files[0]
      const inputFilePath = event.target.value

      const reader = new FileReader()
      console.log('inputFile', inputFile)
      if (inputFile.size < 5242880) {
        const allowedExtensions = /(\.jpg|\.jpeg|\.bmp|\.gif|\.png)$/i

        if (!allowedExtensions.exec(inputFilePath)) {
          this.alert('File type should be jpg/jpeg/png/gif!', 'error')
          return false
        }

        reader.onloadend = () => {
          this.attachtedFileUrl = reader.result
        }
        reader.readAsDataURL(inputFile)
        this.attachtedFile = inputFile
      } else {
        this.alert('File size should be maximus 5MB!', 'error')
      }
    },
    // async uploadFile () {
    //   this.upload_photo = this.$refs.file.files[0]
    //   console.log('this.upload_photo', this.upload_photo)
    //   var formData = new FormData()
    //   if (this.upload_photo) {
    //     formData.append('photo', this.upload_photo)
    //   }
    //   console.log('this.upload_photo', this.upload_photo)
    //   const result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/upload_user_photo', formData)
    //   this.loading = false
    //   if (result.success) {
    //     console.log('result', result)
    //     this.$store.dispatch('Auth/setAuthUser', result.authUser)
    //     this.$toast.success({
    //       title: 'Success',
    //       message: result.message
    //     })
    //     this.$bvModal.hide('modal-1')
    //   } else {
    //     this.$refs.form.setErrors(result.errors)
    //   }
    // },
    async updateData () {
      this.loading = true
      let result = ''

      var formData = new FormData()
      Object.keys(this.form).map(key => {
        formData.append(key, this.form[key])
      })
      if (this.attachtedFile) {
        formData.append('photo', this.attachtedFile)
      }
      result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/profile_update_data', formData)
      this.loading = false
      if (result.success) {
        console.log('result', result)
        this.$store.dispatch('Auth/setAuthUser', result.authUser)
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.$bvModal.hide('modal-1')
      } else {
        this.$refs.form.setErrors(result.errors)
      }
    },
    getProfileList () {
      this.loading = true
      RestApi.getData(baseURL, 'api/v1/admin/ajax/get_current_profile_list', null).then(response => {
        if (response.success) {
          this.form = response.data
          this.loading = false
        } else {
          this.form = []
        }
      })
    }
  }
}
</script>
<style>
.formBoder {
    border: 1px solid;
    margin: 0px;
    padding: 35px;
    font-size: 13px
 }
 .profile {
    margin: 2px 0px 0px 52px;
    font-size: 21px;
    font-family: auto;
  }
</style>
