<template>
  <b-container>
    <b-row>
      <b-col>
        <b-card class="mt-5">
          <h5 class="mb-4">Data Table Standard</h5>
          <div class="table-wrapper">
            <b-table
              :items="items"
              :fields="fields"
              :sort-by.sync="sortBy"
              :sort-desc.sync="sortDesc"
              sort-icon-left
              striped="true"
              hover="true"
              responsive="sm"
            >
              <template v-slot:cell(action)="">
                <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                <a href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
              </template>
            </b-table>
          </div>
        </b-card>
      </b-col>
    </b-row>
    <b-row>
      <b-col>
        <b-card class="mt-5">
          <h5 class="mb-4">Data Table Bordered</h5>
          <div class="table-wrapper">
            <b-table
              :items="items"
              :fields="fields"
              :sort-by.sync="sortBy"
              :sort-desc.sync="sortDesc"
              sort-icon-left
              striped="true"
              hover="true"
              bordered="true"
              responsive="sm"
            >
              <template v-slot:cell(action)="">
                <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                <a href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
              </template>
            </b-table>
          </div>
        </b-card>
      </b-col>
    </b-row>
    <b-row>
      <b-col>
        <b-card class="mt-5">
          <h5 class="mb-4">Data Table Small</h5>
          <div class="table-wrapper">
            <b-table
              :items="items"
              :fields="fields"
              :sort-by.sync="sortBy"
              :sort-desc.sync="sortDesc"
              sort-icon-left
              striped="true"
              hover="true"
              small="true"
              responsive="sm"
            >
              <template v-slot:cell(action)="">
                <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                <a href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
              </template>
            </b-table>
          </div>
        </b-card>
      </b-col>
    </b-row>
    <b-row>
      <b-col>
        <b-card class="mt-5">
          <h5 class="mb-4">Table Bordered</h5>
          <div class="table-wrapper table-responsive">
            <table class="table table-striped table-hover table-bordered">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Division</th>
                  <th scope="col">District</th>
                  <th scope="col">Upozila</th>
                  <th scope="col">Market Name</th>
                  <th scope="col">Complain No</th>
                  <th scope="col">Conviction Date</th>
                  <th scope="col" class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">1</th>
                  <td>Dhaka</td>
                  <td>Dhaka</td>
                  <td>DNCC</td>
                  <td>Abdullahpur Bazar</td>
                  <td>Com-22/01</td>
                  <td>27.01.2022</td>
                  <td class="text-center">
                    <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                    <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                    <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                    <a href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                  </td>
                </tr>
                <tr>
                  <th scope="row">2</th>
                  <td>Chattagram</td>
                  <td>Chandpur</td>
                  <td>Hazigonj</td>
                  <td>Hazigonj Bazar</td>
                  <td>Com-22/02</td>
                  <td>28.01.2022</td>
                  <td class="text-center">
                    <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                    <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                    <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                    <a href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                  </td>
                </tr>
                <tr>
                  <th scope="row">3</th>
                  <td>Barishal</td>
                  <td>Barishal</td>
                  <td>Banaripara</td>
                  <td>Notun Bazar</td>
                  <td>Com-22/03</td>
                  <td>29.01.2022</td>
                  <td class="text-center">
                    <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                    <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                    <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                    <a href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                  </td>
                </tr>
                <tr>
                  <th scope="row">4</th>
                  <td>Khulna</td>
                  <td>Chuadanga</td>
                  <td>Alamdanga</td>
                  <td>Romij Shopping</td>
                  <td>Com-22/04</td>
                  <td>30.01.2022</td>
                  <td class="text-center">
                    <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                    <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                    <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                    <a href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </b-card>
      </b-col>
    </b-row>
    <b-row>
      <b-col>
        <b-card class="mt-4 mb-4">
          <h5 class="mb-4">Table Standard</h5>
          <div class="table-wrapper table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Division</th>
                  <th scope="col">District</th>
                  <th scope="col">Upozila</th>
                  <th scope="col">Market Name</th>
                  <th scope="col">Complain No</th>
                  <th scope="col">Conviction Date</th>
                  <th scope="col" class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">1</th>
                  <td>Dhaka</td>
                  <td>Dhaka</td>
                  <td>DNCC</td>
                  <td>Abdullahpur Bazar</td>
                  <td>Com-22/01</td>
                  <td>27.01.2022</td>
                  <td class="text-center">
                    <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                    <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                    <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                    <a href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                  </td>
                </tr>
                <tr>
                  <th scope="row">2</th>
                  <td>Chattagram</td>
                  <td>Chandpur</td>
                  <td>Hazigonj</td>
                  <td>Hazigonj Bazar</td>
                  <td>Com-22/02</td>
                  <td>28.01.2022</td>
                  <td class="text-center">
                    <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                    <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                    <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                    <a href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                  </td>
                </tr>
                <tr>
                  <th scope="row">3</th>
                  <td>Barishal</td>
                  <td>Barishal</td>
                  <td>Banaripara</td>
                  <td>Notun Bazar</td>
                  <td>Com-22/03</td>
                  <td>29.01.2022</td>
                  <td class="text-center">
                    <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                    <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                    <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                    <a href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                  </td>
                </tr>
                <tr>
                  <th scope="row">4</th>
                  <td>Khulna</td>
                  <td>Chuadanga</td>
                  <td>Alamdanga</td>
                  <td>Romij Shopping</td>
                  <td>Com-22/04</td>
                  <td>30.01.2022</td>
                  <td class="text-center">
                    <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                    <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                    <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                    <a href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </b-card>
      </b-col>
    </b-row>
    <b-row>
      <b-col>
        <b-card class="mb-4">
          <h5 class="mb-4">Table without Bordered</h5>
          <div class="table-wrapper table-responsive">
            <table class="table table-borderless">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Division</th>
                  <th scope="col">District</th>
                  <th scope="col">Upozila</th>
                  <th scope="col">Market Name</th>
                  <th scope="col">Complain No</th>
                  <th scope="col">Conviction Date</th>
                  <th scope="col" class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">1</th>
                  <td>Dhaka</td>
                  <td>Dhaka</td>
                  <td>DNCC</td>
                  <td>Abdullahpur Bazar</td>
                  <td>Com-22/01</td>
                  <td>27.01.2022</td>
                  <td class="text-center">
                    <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                    <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                    <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                    <a href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                  </td>
                </tr>
                <tr>
                  <th scope="row">2</th>
                  <td>Chattagram</td>
                  <td>Chandpur</td>
                  <td>Hazigonj</td>
                  <td>Hazigonj Bazar</td>
                  <td>Com-22/02</td>
                  <td>28.01.2022</td>
                  <td class="text-center">
                    <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                    <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                    <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                    <a href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                  </td>
                </tr>
                <tr>
                  <th scope="row">3</th>
                  <td>Barishal</td>
                  <td>Barishal</td>
                  <td>Banaripara</td>
                  <td>Notun Bazar</td>
                  <td>Com-22/03</td>
                  <td>29.01.2022</td>
                  <td class="text-center">
                    <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                    <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                    <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                    <a href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                  </td>
                </tr>
                <tr>
                  <th scope="row">4</th>
                  <td>Khulna</td>
                  <td>Chuadanga</td>
                  <td>Alamdanga</td>
                  <td>Romij Shopping</td>
                  <td>Com-22/04</td>
                  <td>30.01.2022</td>
                  <td class="text-center">
                    <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                    <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                    <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                    <a href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </b-card>
      </b-col>
    </b-row>
    <b-row>
      <b-col>
        <b-card class="mb-5">
          <h5 class="mb-4">Table Small</h5>
          <div class="table-wrapper table-responsive">
            <table class="table table-striped table-hover table-sm">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Division</th>
                  <th scope="col">District</th>
                  <th scope="col">Upozila</th>
                  <th scope="col">Market Name</th>
                  <th scope="col">Complain No</th>
                  <th scope="col">Conviction Date</th>
                  <th scope="col" class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">1</th>
                  <td>Dhaka</td>
                  <td>Dhaka</td>
                  <td>DNCC</td>
                  <td>Abdullahpur Bazar</td>
                  <td>Com-22/01</td>
                  <td>27.01.2022</td>
                  <td class="text-center">
                    <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                    <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                    <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                    <a href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                  </td>
                </tr>
                <tr>
                  <th scope="row">2</th>
                  <td>Chattagram</td>
                  <td>Chandpur</td>
                  <td>Hazigonj</td>
                  <td>Hazigonj Bazar</td>
                  <td>Com-22/02</td>
                  <td>28.01.2022</td>
                  <td class="text-center">
                    <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                    <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                    <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                    <a href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                  </td>
                </tr>
                <tr>
                  <th scope="row">3</th>
                  <td>Barishal</td>
                  <td>Barishal</td>
                  <td>Banaripara</td>
                  <td>Notun Bazar</td>
                  <td>Com-22/03</td>
                  <td>29.01.2022</td>
                  <td class="text-center">
                    <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                    <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                    <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                    <a href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                  </td>
                </tr>
                <tr>
                  <th scope="row">4</th>
                  <td>Khulna</td>
                  <td>Chuadanga</td>
                  <td>Alamdanga</td>
                  <td>Romij Shopping</td>
                  <td>Com-22/04</td>
                  <td>30.01.2022</td>
                  <td class="text-center">
                    <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                    <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                    <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                    <a href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                  </td>
                </tr>
              </tbody>
            </table>
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
      sortBy: 'division',
      sortDesc: false,
      fields: [
        { key: 'sl', sortable: true },
        { key: 'division', sortable: true },
        { key: 'district', sortable: true },
        { key: 'upozila', sortable: true },
        { key: 'market_name', sortable: true },
        { key: 'complain_no', sortable: true },
        { key: 'conviction_date', sortable: true },
        { key: 'action', sortable: false }
      ],
      items: [
        {
          isActive: true,
          sl: 1,
          division: 'Dhaka',
          district: 'Dhaka',
          upozila: 'DNCC',
          market_name: 'Abdullahpur Bazar',
          complain_no: 'Com-22/01',
          conviction_date: '27.01.2022',
          action: ''
        },
        {
          isActive: true,
          sl: 2,
          division: 'Chattagram',
          district: 'Chandpur',
          upozila: 'Hazigonj',
          market_name: 'Hazigonj Bazar',
          complain_no: 'Com-22/02',
          conviction_date: '28.01.2022',
          action: ''
        },
        {
          isActive: true,
          sl: 3,
          division: 'Barishal',
          district: 'Barishal',
          upozila: 'Banaripara',
          market_name: 'Notun Bazar',
          complain_no: 'Com-22/03',
          conviction_date: '29.01.2022',
          action: ''
        },
        {
          isActive: true,
          sl: 4,
          division: 'Khulna',
          district: 'Chuadanga',
          upozila: 'Alamdanga',
          market_name: 'Romij Shopping',
          complain_no: 'Com-22/04',
          conviction_date: '30.01.2022',
          action: ''
        }
      ]
    }
  }
}
</script>
