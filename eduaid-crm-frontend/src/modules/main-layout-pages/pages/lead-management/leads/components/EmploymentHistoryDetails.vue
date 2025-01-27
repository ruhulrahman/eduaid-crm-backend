<template>
    <b-row>
        <b-col lg="6" md="6" sm="12" xs="12">
            <b-card>
                <b-card-text>
                <p class="text-success mb-2" v-if="detailsDatas.organization_name"><b>Organization Name:</b> <span v-html="detailsDatas.organization_name"></span></p>
                <p class="mb-2" v-if="detailsDatas.dependent"><b>Dependent:</b> <span v-html="detailsDatas.dependent.name"></span></p>
                <!-- <p class="mb-2"><b>Designation:</b> <span v-html="detailsDatas.designation"></span></p> -->
                <p class="mb-2" v-if="detailsDatas.start_date"><b>Start Date:</b> <span v-html="dDate(detailsDatas.start_date)"></span></p>
                <p class="mb-2" v-if="detailsDatas.end_date"><b>End Date:</b> <span v-html="dDate(detailsDatas.end_date)"></span></p>
                <p class="mb-2"><b class="pr-1">Still Working:</b>
                  <span class="badge badge-success badge-pill" v-if="detailsDatas.still_working">Yes</span>
                  <span class="badge badge-danger badge-pill" v-else>No</span>
                </p>
                <p class="mb-2"><b class="pr-1">Is Primary:</b>
                  <span class="badge badge-success badge-pill" v-if="detailsDatas.is_primary">Yes</span>
                  <span class="badge badge-danger badge-pill" v-else>No</span>
                </p>
                </b-card-text>
            </b-card>
        </b-col>
        <b-col lg="6" md="6" sm="12" xs="12">
            <b-card>
                <b-card-text>
                <p class="text-success mb-2" v-if="detailsDatas.responsibility"><b>Responsibility:</b> <span v-html="detailsDatas.responsibility"></span></p>
                <p class="mb-2" v-if="detailsDatas.designation"><b>Designation:</b> <span v-html="detailsDatas.designation"></span></p>
                <p class="mb-2" v-if="detailsDatas.country"><b>Country:</b> <span v-html="detailsDatas.country.name"></span></p>
                <p class="mb-2" v-if="detailsDatas.location"><b>Location:</b> <span v-html="detailsDatas.location"></span></p>
                <p class="mb-2"><b class="pr-1">Is Foreign:</b>
                <span class="badge badge-success badge-pill" v-if="detailsDatas.is_foreign">Yes</span>
                <span class="badge badge-danger badge-pill"  v-else>No</span>
                </p>
                </b-card-text>
            </b-card>
        </b-col>
    </b-row>
</template>
<script>
export default {
  props: ['DetailsItem'],
  data () {
    return {
      detailsDatas: []
    }
  },
  created () {
    this.detailsDatas = JSON.parse(this.DetailsItem)
  }
}
</script>
<style>
</style>
