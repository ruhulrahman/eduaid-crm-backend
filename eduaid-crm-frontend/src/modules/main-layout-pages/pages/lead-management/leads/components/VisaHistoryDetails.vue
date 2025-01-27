<template>
    <b-row>
        <b-col lg="6" md="6" sm="12" xs="12" offset="2">
            <b-card>
                <b-card-text>
                    <p class="text-success mb-2" v-if="detailsDatas.dependent"><b>Dependent:</b> <span v-html="detailsDatas.dependent.name"></span></p>
                    <p class="mb-2" v-if="detailsDatas.visa_type"><b>Visa Type:</b> <span v-html="detailsDatas.visa_type.name"></span></p>
                    <p class="mb-2" v-if="detailsDatas.start_date"><b>Start Date:</b> <span v-html="detailsDatas.start_date"></span></p>
                    <p class="mb-2" v-if="detailsDatas.end_date"><b>End Date:</b> <span v-html="detailsDatas.end_date"></span></p>
                    <p class="mb-2" v-if="detailsDatas.rejection_date"><b>Rejection Date:</b> <span v-html="detailsDatas.rejection_date"></span></p>
                    <p class="mb-2" v-if="detailsDatas.rejection_reason"><b>Rejection Reason:</b> <span v-html="detailsDatas.rejection_reason"></span></p>
                    <p class="mb-2" v-if="detailsDatas.country"><b>Country:</b> <span v-html="detailsDatas.country.name"></span></p>
                    <p class="mb-2" v-if="detailsDatas.purpose"><b>Purpose:</b> <span v-html="detailsDatas.purpose"></span></p>
                    <p class="mb-2" v-if="detailsDatas.media"><b>File:</b> <a target="_blank" :href="detailsDatas.attachment" title="file" class="mr-3"><i class="ri-download-cloud-fill"></i></a></p>
                </b-card-text>
            </b-card>
        </b-col>
        <!-- <pre>{{ detailsDatas }}</pre> -->
    </b-row>
</template>
<script>
export default {
  props: ['detailItems'],
  data () {
    return {
      detailsDatas: []
    }
  },
  created () {
    this.detailsDatas = JSON.parse(this.detailItems)
    // console.log('detailsDatas', this.detailsDatas)
  }
}
</script>
<style>
</style>
