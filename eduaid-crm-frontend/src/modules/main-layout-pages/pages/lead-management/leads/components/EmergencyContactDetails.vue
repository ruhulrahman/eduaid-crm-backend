<template>
    <b-row>
        <b-col lg="8" md="8" sm="12" xs="12" offset="2">
            <b-card>
                <b-card-text>
                <p class="text-success mb-2" v-if="detailsDatas.dependent_id"><b>Dependent:</b> <span v-html="detailsDatas.dependent.name"></span></p>
                <p class="mb-2" v-if="detailsDatas.name"><b>Name:</b> <span v-html="detailsDatas.name"></span></p>
                <p class="mb-2" v-if="detailsDatas.mobile"><b>Mobile:</b> <span v-html="detailsDatas.mobile"></span></p>
                <p class="mb-2" v-if="detailsDatas.address"><b>Address:</b> <span v-html="detailsDatas.address"></span></p>
                <p class="mb-2" v-if="detailsDatas.mobile_country"><b>Country:</b> <span v-html="detailsDatas.mobile_country.name"></span></p>
                <p class="mb-2" v-if="detailsDatas.email"><b>Email:</b> <span v-html="detailsDatas.email"></span></p>
                <p class="mb-2" v-if="detailsDatas.relation"><b>Relation:</b> <span v-html="detailsDatas.relation"></span></p>
                <p class="mb-2" v-if="detailsDatas.note"><b>Note:</b> <span v-html="detailsDatas.note"></span></p>
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
  }
}
</script>
<style>
</style>
