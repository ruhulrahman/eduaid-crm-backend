<template>
    <b-container>
        <b-row>
            <b-col>
                <b-card class="mt-5">
                    <h5 class="mb-4">Pagination</h5>
                    <div class="overflow-auto">
                        <div>
                        <h6 class="mb-2" style="color: #ccc">Small</h6>
                        <b-pagination v-model="currentPage" :total-rows="rows" size="sm"></b-pagination>
                        </div>
                        <div class="mt-3">
                        <h6 class="mb-2" style="color: #ccc">Default</h6>
                        <b-pagination v-model="currentPage" :total-rows="rows"></b-pagination>
                        </div>
                        <div class="mt-3">
                        <h6 class="mb-2" style="color: #ccc">Large</h6>
                        <b-pagination v-model="currentPage" :total-rows="rows" size="lg"></b-pagination>
                        </div>
                    </div>
                </b-card>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
export default {
  data () {
    return {
      rows: 100,
      currentPage: 1
    }
  }
}
</script>
