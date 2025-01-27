<template>
    <b-container>
        <b-row>
            <b-col class="mt-5">
                <b-card>
                    <h5 class="mb-4">Breadcrumb</h5>
                    <b-breadcrumb :items="items"></b-breadcrumb>
                </b-card>
            </b-col>
        </b-row>
        <b-row>
            <b-col class="mt-5">
                <b-card>
                    <h5 class="mb-4">Custom Breadcrumb</h5>
                    <b-breadcrumb class="custom-bread" :items="items"></b-breadcrumb>
                </b-card>
            </b-col>
        </b-row>
        <b-row class="mb-5"></b-row>
    </b-container>
</template>
<script>
export default {
  data () {
    return {
      items: [
        {
          text: 'Admin',
          href: '#'
        },
        {
          text: 'Manage',
          href: '#'
        },
        {
          text: 'Library',
          active: true
        }
      ]
    }
  }
}
</script>
