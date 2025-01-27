<template>
  <b-row>
    <!-- <pre> {{ detailsDatas }}</pre> -->
      <b-col lg="6" md="6" sm="12" xs="12">
          <b-card>
              <b-card-text>
                <p class="text-success mb-2" v-if="detailsDatas.dependent"><b>Dependent:</b> <span v-html="detailsDatas.dependent.name"></span></p>
                <p class="mb-2" v-if="detailsDatas.country"><b>institute:</b> <span v-html="detailsDatas.country.name"></span></p>
                <p class="mb-2" v-if="detailsDatas.institute_name"><b>Institute Name:</b> <span v-html="detailsDatas.institute_name"></span></p>
                <p class="mb-2"><b class="pr-1">Is Foreign Institute:</b>
                  <span class="badge badge-success badge-pill" v-if="detailsDatas.is_foreign_institute">Yes</span>
                  <span class="badge badge-danger badge-pill" v-else>No</span>
                </p>
                <p class="mb-2" v-if="detailsDatas.course_level"><b>Course Level</b> <span v-html="detailsDatas.course_level.name"></span></p>
                <p class="mb-2" v-if="detailsDatas.course_name"><b>Course Name</b> <span v-html="detailsDatas.course_name"></span></p>
                <p class="mb-2" v-if="detailsDatas.duration"><b>Duration:</b> <span v-html="detailsDatas.duration"></span></p>
              </b-card-text>
          </b-card>
      </b-col>
      <b-col lg="6" md="6" sm="12" xs="12">
          <b-card>
              <b-card-text>
              <!-- <p class="text-success mb-2" v-if="detailsDatas.course_name"><b>Course Name:</b> <span v-html="detailsDatas.course_name"></span></p> -->
              <p class="mb-2" v-if="detailsDatas.result_type.name"><b>Result Type:</b> <span v-html="detailsDatas.result_type.name"></span></p>
              <p class="mb-2" v-if="detailsDatas.marks"><b>Marks:</b> <span v-html="detailsDatas.marks"></span></p>
              <p class="mb-2" v-if="detailsDatas.grade"><b>Grade:</b> <span v-html="detailsDatas.grade"></span></p>
              <p class="mb-2" v-if="detailsDatas.grade_scale"><b>Grade scale:</b> <span v-html="detailsDatas.grade_scale"></span></p>
              <p class="mb-2" v-if="detailsDatas.year_of_passing"><b>Passing Year:</b> <span v-html="detailsDatas.year_of_passing"></span></p>
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
