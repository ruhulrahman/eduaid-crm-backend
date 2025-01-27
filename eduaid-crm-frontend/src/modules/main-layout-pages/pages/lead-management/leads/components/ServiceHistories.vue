<template>
    <b-row>
      <b-col lg="12" md="12" sm="12" xs="12">
          <b-card>
            <div class="table-wrapper table-responsive mt-0 mb-0">
              <table class="table table-striped table-hover table-bordered">
                <thead>
                  <tr style="font-size: 12px;">
                    <th scope="col" class="text-center">SL</th>
                    <th scope="col" class="text-center">Country</th>
                    <th scope="col" class="text-center">Service</th>
                    <th scope="col" class="text-center">Service Status</th>
                    <th scope="col" class="text-center">Created At</th>
                  </tr>
                </thead>
                <template v-if="serviceHistories.length">
                <tbody v-for="(item, index) in serviceHistories" :key="index">
                  <tr style="font-size: 12px;">
                    <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                    <td class="text-left">
                      <span v-if="item.country" v-html="item.country.name"></span>
                    </td>
                    <td class="text-left">
                      <span v-if="item.service" v-html="item.service.name"></span>
                    </td>
                    <td class="text-left">
                      <span v-if="item.service_status" v-html="item.service_status.name"></span>
                    </td>
                  <td class="text-center">
                    <span v-if="item.created_at" v-html="dDate(item.created_at)"></span>
                  </td>
                  </tr>
                </tbody>
              </template>
              <template v-else>
                  <tr>
                    <td class="text-center" colspan="12">No Data Found</td>
                  </tr>
              </template>
              </table>
            </div>
          </b-card>
      </b-col>
    </b-row>
</template>
<script>
export default {
  props: ['editItem'],
  data () {
    return {
      serviceHistories: []
    }
  },
  created () {
    if (this.editItem) {
      this.serviceHistories = JSON.parse(this.editItem)
    }
  }
}
</script>
<style>
</style>
