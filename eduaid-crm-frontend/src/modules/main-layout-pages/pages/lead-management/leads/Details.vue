<template>
  <div class="section-wrapper">
    <b-breadcrumb>
      <b-breadcrumb-item to="/dashboard">
        <b-icon icon="house-fill" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
        Dashboard
      </b-breadcrumb-item>
      <b-breadcrumb-item>Lead Management</b-breadcrumb-item>
      <b-breadcrumb-item to="/leads">Leads</b-breadcrumb-item>
      <b-breadcrumb-item active>Details</b-breadcrumb-item>
    </b-breadcrumb>
    <b-row>
      <b-col lg="4" md="4" sm="12" xs="12">
        <b-card>
          <b-card-title>
            <b-row>
              <b-col>
                <h4 class="card-title mb-0 pl-0">Lead Info</h4>
              </b-col>
              <b-col class="text-right">
                <a v-tooltip="'Edit'" v-if="has_permission('permitted')" href="javascript:" class="action-btn btn-edit" @click="editData(leadDetails)"><i class="ri-pencil-fill"></i></a>
              </b-col>
            </b-row>
          </b-card-title>
          <b-card-text v-if="leadDetails" class="font-size-12">
            <p class="text-success mb-2" v-if="leadDetails.full_name"><b>Full Name:</b> <span v-html="leadDetails.full_name"></span></p>
            <p class="mb-2" v-if="leadDetails.nick_name"><b>Nick Name:</b> <span v-html="leadDetails.nick_name"></span></p>
            <p class="mb-2" v-if="leadDetails.email"><b>Email:</b> <span v-html="leadDetails.email"></span></p>
            <p class="mb-2" v-if="leadDetails.other_email"><b>Other Email:</b> <span v-html="leadDetails.other_email"></span></p>
            <p class="mb-2" v-if="leadDetails.mobile"><b class="pr-1">Mobile:</b>
              <span v-if="leadDetails.mobile_country" v-html="`+${leadDetails.mobile_country.calling_code}${leadDetails.mobile}`"></span>
            </p>
            <p class="mb-2" v-if="leadDetails.alternative_mobile"><b class="pr-1">Alternative Mobile:</b>
              <span v-if="leadDetails.mobile_country" v-html="`+${leadDetails.mobile_country.calling_code}${leadDetails.alternative_mobile}`"></span>
            </p>
            <p class="mb-2" v-if="leadDetails.description"><b>Description:</b> <span v-html="leadDetails.description"></span></p>
            <p class="mb-2" v-if="leadDetails.dob"><b>Date of Birth:</b> <span v-html="dDate(leadDetails.dob)"></span></p>
            <p class="mb-2" v-if="leadDetails.enlistment_date"><b>Enlistment Date:</b> <span v-html="dDate(leadDetails.enlistment_date)"></span></p>
            <p class="mb-2" v-if="leadDetails.country"><b>Country:</b> <span v-html="leadDetails.country.name"></span></p>
            <p class="mb-2" v-if="leadDetails.supervisor"><b>Supervisor:</b> <span v-html="leadDetails.supervisor.name"></span></p>
            <p class="mb-2"><b class="pr-1">Is Married:</b>
              <span v-if="leadDetails.is_married" class="badge badge-success badge-pill">Married</span>
              <span v-else class="badge badge-danger badge-pill">Unmarried</span>
            </p>
            <p class="mb-2"><b class="pr-1">Is Client:</b>
              <span v-if="leadDetails.is_client" class="badge badge-success badge-pill">Yes</span>
              <span v-else class="badge badge-danger badge-pill">No</span>
            </p>
            <p class="mb-2"><b class="pr-1">Status:</b>
              <span v-if="leadDetails.active" class="badge badge-success badge-pill">Active</span>
              <span v-else class="badge badge-danger badge-pill">Inactive</span>
            </p>
            <p class="mb-2"><b class="pr-1">Present Address:</b>
              <span v-if="leadDetails.present_address" class="" v-html="leadDetails.present_address"></span>
              <span v-if="leadDetails.pre_city" class="" v-html="`, ${leadDetails.pre_city}`"></span>
              <span v-if="leadDetails.pre_post_code" class="" v-html="`, ${leadDetails.pre_post_code}`"></span>
            </p>
            <p class="mb-2"><b class="pr-1">Permanent Address:</b>
              <span v-if="leadDetails.permanent_address" class="" v-html="leadDetails.permanent_address"></span>
              <span v-if="leadDetails.per_city" class="" v-html="`, ${leadDetails.per_city}`"></span>
              <span v-if="leadDetails.per_post_code" class="" v-html="`, ${leadDetails.per_post_code}`"></span>
            </p>
          </b-card-text>
        </b-card>
      </b-col>

      <b-col lg="8" md="8" sm="12" xs="12">
        <b-row>
          <!-- Lead Services Start -->
          <b-col class="" lg="12" md="12" sm="12" xs="12">
            <b-row class="">
              <b-col lg="12" md="12" sm="12" xs="12">
                <b-card>
                  <b-card-title>
                    <b-row>
                      <b-col>
                        <h4 class="card-title mb-0 pl-0">Services</h4>
                      </b-col>
                      <b-col class="text-right">
                        <b-button v-if="has_permission('permitted')" size="sm" variant="primary" @click="openAddNewServiceModal()">Add New<i class="ri-add-fill"></i></b-button>
                      </b-col>
                    </b-row>
                  </b-card-title>
                  <b-overlay :show="loading">
                    <div class="table-wrapper table-responsive mb-0">
                      <table class="table table-hover table-bordered">
                        <thead>
                          <tr style="font-size: 12px;">
                            <th scope="col" class="text-center">SL</th>
                            <th scope="col" class="text-center">Country</th>
                            <th scope="col" class="text-center">Service</th>
                            <th scope="col" class="text-center">Service Status</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Action</th>
                          </tr>
                        </thead>
                        <template v-if="Object.keys(leadDetails.services || { }).length">
                        <tbody v-for="(item, index) in leadDetails.services" :key="index">
                          <tr style="font-size: 12px;">
                            <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                            <td class="text-left">
                              <span v-if="item.country" v-html="item.country.name"></span>
                            </td>
                            <td class="text-left">
                              <span v-if="item.service" v-html="item.service.name"></span>
                            </td>
                            <td class="text-center">
                              <span v-if="item.service_status" v-html="item.service_status.name"></span>
                            </td>
                            <td class="text-center">
                              <b-form-checkbox v-if="has_permission('permitted')" @change="toggleLeadServiceActiveStatus(item)" v-model="item.active" name="check-button" switch>
                              </b-form-checkbox>
                            </td>
                            <td class="text-center">
                              <a v-tooltip="'Edit'" v-if="has_permission('permitted')" @click="editServiceDataModal(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                              <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteService(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                              <a v-tooltip="'View Service Histories'" v-if="has_permission('permitted') && item.service_histories.length" @click="openServiceHistoryModal(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                              <!-- <a v-tooltip="'View Service Histories'" v-if="has_permission('permitted') && item.service_histories.length" @click="showServiceDetails(item, index)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a> -->
                            </td>
                            <tr v-if="serviceDetails && serviceItemIndex == index">
                              <td colspan="6">
                                <h6 class="text-primary">History</h6>
                                <div class="table-wrapper table-responsive mt-0">
                                  <table class="table table-hover table-bordered">
                                    <thead>
                                      <tr style="font-size: 12px;">
                                        <th scope="col" class="text-center">SL</th>
                                        <th scope="col" class="text-center">Country</th>
                                        <th scope="col" class="text-center">Service</th>
                                        <th scope="col" class="text-center">Service Status</th>
                                        <th scope="col" class="text-center">Created At</th>
                                      </tr>
                                    </thead>
                                    <template v-if="serviceDetails.length">
                                    <tbody v-for="(item, index) in serviceDetails" :key="index">
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
                              </td>
                            </tr>
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
                  </b-overlay>
                </b-card>
            </b-col>
            </b-row>
          </b-col>
          <!-- Lead Services End -->
        </b-row>

        <!-- Denpendent Info Start -->
        <b-row class="mt-4">
          <b-col lg="12" md="12" sm="12" xs="12">
            <b-card>
              <b-card-title>
                <b-row>
                  <b-col>
                    <h4 class="card-title mb-0 pl-0">Denpendent Info</h4>
                  </b-col>
                  <b-col class="text-right">
                    <b-button v-if="has_permission('permitted')" size="sm" variant="primary" @click="openAddNewDependentModal()">Add New<i class="ri-add-fill"></i></b-button>
                  </b-col>
                </b-row>
              </b-card-title>
              <b-overlay :show="loading">
                <div class="table-wrapper table-responsive mb-0">
                  <table class="table table-hover table-bordered">
                    <thead>
                      <tr style="font-size: 12px;">
                        <th scope="col" class="text-center">SL</th>
                        <th scope="col" class="text-left">Name & Type</th>
                        <th scope="col" class="text-center">Email</th>
                        <th scope="col" class="text-center">Mobile & Alternative Mobile</th>
                        <th scope="col" class="text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody v-for="(item, index) in leadDetails.dependents" :key="index">
                      <tr style="font-size: 12px;">
                        <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                        <td class="text-left">
                          <span v-html="item.name"></span><br/>
                          <span class="badge badge-info badge-pill" v-html="item.type"></span>
                        </td>
                        <td class="text-left"><span v-html="item.email"></span></td>
                        <td class="text-center">
                          <span v-if="item.mobile_country" v-html="`+${item.mobile_country.calling_code}${item.mobile}`"></span><br/>
                          <span v-if="item.mobile_country && item.alt_mobile" v-html="`+${item.mobile_country.calling_code}${item.alt_mobile}`"></span>
                        </td>
                        <td class="text-center">
                          <a v-tooltip="'Edit'" v-if="has_permission('permitted')" @click="editDependentData(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                          <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteDependentData(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </b-overlay>
            </b-card>
          </b-col>
        </b-row>
        <!-- Denpendent Info End -->

      <!-- Lead Social Start -->
        <b-row class="mt-4">
          <b-col lg="12" md="12" sm="12" xs="12">
            <b-card>
              <b-card-title>
                <b-row>
                  <b-col>
                    <h4 class="card-title mb-0 pl-0">Socials</h4>
                  </b-col>
                  <b-col class="text-right">
                    <b-button v-if="has_permission('permitted')" size="sm" variant="primary" @click="openAddNewSocialModal()">Add New<i class="ri-add-fill"></i></b-button>
                  </b-col>
                </b-row>
              </b-card-title>
              <b-row class="mt-3">
                <b-col>
                  <h6 class="pl-2 text-success">Candidate Socials</h6>
                </b-col>
              </b-row>
              <b-overlay :show="loading">
                <div class="table-wrapper table-responsive mt-2 mb-0">
                  <table class="table table-hover table-bordered">
                    <thead>
                      <tr style="font-size: 12px;">
                        <th scope="col" class="text-center">SL</th>
                        <th scope="col" class="text-left">Media Name</th>
                        <th scope="col" class="text-center">Url</th>
                        <th scope="col" class="text-center">Active</th>
                        <th scope="col" class="text-center">Action</th>
                      </tr>
                    </thead>
                    <template v-if="Object.keys(leadDetails.candidate_socials || {}).length">
                      <tbody v-for="(item, index) in leadDetails.candidate_socials" :key="index">
                        <tr style="font-size: 12px;">
                          <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                          <td class="text-left">
                            <span v-if="item.social" class="d-flex align-items-center">
                              <i v-if="item.social.icon_name == 'remix'" v-tooltip="`${item.social.media_name}`" :style="`color: ${item.social.icon_color}`" class="font-size-18 mr-1" :class="item.social.icon_value"></i>
                              <a :href="item.social_url" v-html="item.social.media_name" target="_blank"></a>
                            </span>
                          </td>
                          <td class="text-left">
                            <a :href="item.social_url" v-html="item.social_url" target="_blank"></a>
                          </td>
                          <td class="text-center">
                            <b-form-checkbox v-if="has_permission('active_or_deactive_subscription_plan')" @change="toggleLeadSocialActiveStatus(item)" v-model="item.active" name="check-button" switch>
                            </b-form-checkbox>
                          </td>
                          <td class="text-center">
                            <a v-tooltip="'Edit'" v-if="has_permission('permitted')" @click="editSocialData(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                            <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteSocialData(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                          </td>
                        </tr>
                      </tbody>
                    </template>
                    <template v-else>
                        <tr>
                          <td class="text-center" colspan="9">No Data Found</td>
                        </tr>
                    </template>
                  </table>
                </div>
              </b-overlay>
              <b-row class="mt-3">
                <b-col>
                  <h6 class="pl-2 text-success">Dependent Socials</h6>
                </b-col>
              </b-row>
              <b-overlay :show="loading">
                <div class="table-wrapper table-responsive mt-2 mb-0">
                  <table class="table table-hover table-bordered">
                    <thead>
                      <tr style="font-size: 12px;">
                        <th scope="col" class="text-center">SL</th>
                        <th scope="col" class="text-left">Media Name</th>
                        <th scope="col" class="text-center">Dependent</th>
                        <th scope="col" class="text-center">Url</th>
                        <th scope="col" class="text-center">Active</th>
                        <th scope="col" class="text-center">Action</th>
                      </tr>
                    </thead>
                    <template v-if="Object.keys(leadDetails.dependent_socials || {}).length">
                      <tbody v-for="(item, index) in leadDetails.dependent_socials" :key="index">
                        <tr style="font-size: 12px;">
                          <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                          <td class="text-left">
                            <span v-if="item.social" class="d-flex align-items-center">
                              <i v-if="item.social.icon_name == 'remix'" v-tooltip="`${item.social.media_name}`" :style="`color: ${item.social.icon_color}`" class="font-size-18 mr-1" :class="item.social.icon_value"></i>
                              <a :href="item.social_url" v-html="item.social.media_name" target="_blank"></a>
                            </span>
                          </td>
                          <td class="text-left">
                            <span v-if="item.dependent" v-html="item.dependent.name"></span>
                          </td>
                          <td class="text-left">
                            <a :href="item.social_url" v-html="item.social_url" target="_blank"></a>
                          </td>
                          <td class="text-center">
                            <b-form-checkbox v-if="has_permission('active_or_deactive_subscription_plan')" @change="toggleLeadSocialActiveStatus(item)" v-model="item.active" name="check-button" switch>
                            </b-form-checkbox>
                          </td>
                          <td class="text-center">
                            <a v-tooltip="'Edit'" v-if="has_permission('permitted')" @click="editSocialData(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                            <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteSocialData(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                          </td>
                        </tr>
                      </tbody>
                    </template>
                    <template v-else>
                        <tr>
                          <td class="text-center" colspan="9">No Data Found</td>
                        </tr>
                    </template>
                  </table>
                </div>
              </b-overlay>
            </b-card>
          </b-col>
        </b-row>
      <!-- Lead Social End -->
      </b-col>

      <!-- Lead Contact Start -->
      <b-col class="" lg="12" md="12" sm="12" xs="12">
        <b-row class="mt-4">
          <b-col lg="12" md="12" sm="12" xs="12">
            <b-card>
              <b-card-title>
                <b-row>
                  <b-col>
                    <h4 class="card-title mb-0 pl-0">Contacts</h4>
                  </b-col>
                  <b-col class="text-right">
                    <b-button v-if="has_permission('permitted')" size="sm" variant="primary" @click="openAddNewContactModal()">Add New<i class="ri-add-fill"></i></b-button>
                  </b-col>
                </b-row>
              </b-card-title>
              <b-row class="mt-3">
                <b-col>
                  <h6 class="pl-2 text-success">Candidate Contacts</h6>
                </b-col>
              </b-row>
              <b-overlay :show="loading">
                <div class="table-wrapper table-responsive mt-2">
                  <table class="table table-hover table-bordered">
                    <thead>
                      <tr style="font-size: 12px;">
                        <th scope="col" class="text-center">SL</th>
                        <th scope="col" class="text-center">Contact Time</th>
                        <th scope="col" class="text-center">Contact Preference</th>
                        <th scope="col" class="text-center">Note</th>
                        <th scope="col" class="text-center">Action</th>
                      </tr>
                    </thead>
                    <template v-if="Object.keys(leadDetails.candidate_contacts || {}).length">
                      <tbody v-for="(item, index) in leadDetails.candidate_contacts" :key="index">
                        <tr style="font-size: 12px;">
                          <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                          <td class="text-left">
                            <span v-html="item.contact_time"></span>
                          </td>
                          <td class="text-left">
                            <span v-html="item.contact_preference"></span>
                          </td>
                          <td class="text-left">
                            <span v-html="item.note"></span>
                          </td>
                          <td class="text-center">
                            <a v-tooltip="'Edit'" v-if="has_permission('permitted')" @click="editContactData(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                            <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteContactData(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                          </td>
                        </tr>
                      </tbody>
                    </template>
                    <template v-else>
                        <tr>
                          <td class="text-center" colspan="9">No Data Found</td>
                        </tr>
                    </template>
                  </table>
                </div>
              </b-overlay>
              <b-row class="mt-3">
                <b-col>
                  <h6 class="pl-2 text-success">Dependent Contacts</h6>
                </b-col>
              </b-row>
              <b-overlay :show="loading">
                <div class="table-wrapper table-responsive mt-2 mb-0">
                  <table class="table table-hover table-bordered">
                    <thead>
                      <tr style="font-size: 12px;">
                        <th scope="col" class="text-center">SL</th>
                        <th scope="col" class="text-center">Dependent</th>
                        <th scope="col" class="text-center">Contact Time</th>
                        <th scope="col" class="text-center">Contact Preference</th>
                        <th scope="col" class="text-center">Note</th>
                        <th scope="col" class="text-center">Action</th>
                      </tr>
                    </thead>
                    <template v-if="Object.keys(leadDetails.dependent_contacts || {}).length">
                      <tbody v-for="(item, index) in leadDetails.dependent_contacts" :key="index">
                        <tr style="font-size: 12px;">
                          <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                          <td class="text-left">
                            <span v-if="item.dependent" v-html="item.dependent.name"></span>
                          </td>
                          <td class="text-left">
                            <span v-html="item.contact_time"></span>
                          </td>
                          <td class="text-left">
                            <span v-html="item.contact_preference"></span>
                          </td>
                          <td class="text-left">
                            <span v-html="item.note"></span>
                          </td>
                          <td class="text-center">
                            <a v-tooltip="'Edit'" v-if="has_permission('permitted')" @click="editContactData(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                            <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteContactData(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                          </td>
                        </tr>
                      </tbody>
                    </template>
                    <template v-else>
                        <tr>
                          <td class="text-center" colspan="9">No Data Found</td>
                        </tr>
                    </template>
                  </table>
                </div>
              </b-overlay>
            </b-card>
          </b-col>
        </b-row>
      </b-col>
      <!-- Lead Contact End -->

      <!-- Lead Emergency Contact Start -->
      <b-col class="" lg="12" md="12" sm="12" xs="12">
        <b-row class="mt-4">
           <b-col lg="12" md="12" sm="12" xs="12">
             <b-card>
               <b-card-title>
                 <b-row>
                   <b-col>
                     <h4 class="card-title mb-0 pl-0">Emergency Contacts</h4>
                   </b-col>
                   <b-col class="text-right">
                     <b-button v-if="has_permission('permitted')" size="sm" variant="primary" @click="openAddNewEmergencyContactModal()">Add New<i class="ri-add-fill"></i></b-button>
                   </b-col>
                 </b-row>
               </b-card-title>
                <b-row class="mt-3">
                  <b-col>
                    <h6 class="pl-2 text-success">Candidate Emergency Contacts</h6>
                  </b-col>
                </b-row>
               <b-overlay :show="loading">
                 <div class="table-wrapper table-responsive mt-2">
                   <table class="table table-hover table-bordered">
                     <thead>
                       <tr style="font-size: 12px;">
                         <th scope="col" class="text-center">SL</th>
                         <th scope="col" class="text-center">Name</th>
                         <th scope="col" class="text-center">Mobile</th>
                         <th scope="col" class="text-center">Email</th>
                         <th scope="col" class="text-center">Address</th>
                         <th scope="col" class="text-center">Relation</th>
                         <th scope="col" class="text-center">Note</th>
                         <th scope="col" class="text-center">Action</th>
                       </tr>
                     </thead>
                     <template v-if="Object.keys(leadDetails.candidate_emergency_contacts || {}).length">
                     <tbody v-for="(item, index) in leadDetails.candidate_emergency_contacts" :key="index">
                       <tr style="font-size: 12px;">
                         <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                         <td class="text-left">
                           <span v-html="item.name"></span>
                         </td>
                         <td class="text-left">
                           <span v-html="item.mobile"></span>
                         </td>
                         <td class="text-center">
                           <span v-html="item.email"></span>
                         </td>
                         <td class="text-left">
                           <span v-html="item.address"></span>
                         </td>
                         <td class="text-left">
                           <span v-html="item.relation"></span>
                         </td>
                         <td class="text-left">
                           <span v-html="item.note"></span>
                         </td>
                         <td class="text-center">
                           <a v-tooltip="'Edit'" v-if="has_permission('permitted')" @click="editEmerContactData(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                           <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteEmerContactData(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                           <a v-tooltip="'Details'" v-if="has_permission('permitted')" @click="detailsDataEmergencyContact(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                         </td>
                       </tr>
                     </tbody>
                    </template>
                    <template v-else>
                        <tr>
                          <td class="text-center" colspan="9">No Data Found</td>
                        </tr>
                    </template>
                   </table>
                 </div>
               </b-overlay>
                <b-row class="mt-3">
                  <b-col>
                    <h6 class="pl-2 text-success">Dependent Emergency Contacts</h6>
                  </b-col>
                </b-row>
               <b-overlay :show="loading">
                 <div class="table-wrapper table-responsive mt-2 mb-0">
                   <table class="table table-hover table-bordered">
                     <thead>
                       <tr style="font-size: 12px;">
                         <th scope="col" class="text-center">SL</th>
                         <th scope="col" class="text-center">Dependent</th>
                         <th scope="col" class="text-center">Name</th>
                         <th scope="col" class="text-center">Mobile</th>
                         <th scope="col" class="text-center">Email</th>
                         <th scope="col" class="text-center">Address</th>
                         <!-- <th scope="col" class="text-center">Relation</th>
                         <th scope="col" class="text-center">Note</th> -->
                         <th scope="col" class="text-center">Action</th>
                       </tr>
                     </thead>
                     <template v-if="Object.keys(leadDetails.dependent_emergency_contacts || {}).length">
                     <tbody v-for="(item, index) in leadDetails.dependent_emergency_contacts" :key="index">
                       <tr style="font-size: 12px;">
                         <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                         <td class="text-left">
                           <span v-if="item.dependent" v-html="item.dependent.name"></span>
                         </td>
                         <td class="text-left">
                           <span v-html="item.name"></span>
                         </td>
                         <td class="text-left">
                           <span v-html="item.mobile"></span>
                         </td>
                         <td class="text-center">
                           <span v-html="item.email"></span>
                         </td>
                         <td class="text-left">
                           <span v-html="item.address"></span>
                         </td>
                         <!-- <td class="text-left">
                           <span v-html="item.relation"></span>
                         </td>
                         <td class="text-left">
                           <span v-html="item.note"></span>
                         </td> -->
                         <td class="text-center">
                           <a v-tooltip="'Edit'" v-if="has_permission('permitted')" @click="editEmerContactData(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                           <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteEmerContactData(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                           <a v-tooltip="'Details'" v-if="has_permission('permitted')" @click="detailsDataEmergencyContact(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                         </td>
                       </tr>
                     </tbody>
                    </template>
                    <template v-else>
                        <tr>
                          <td class="text-center" colspan="9">No Data Found</td>
                        </tr>
                    </template>
                   </table>
                 </div>
               </b-overlay>
             </b-card>
           </b-col>
         </b-row>
      </b-col>
      <!-- Lead Emergency Contact End -->

      <!-- Lead Employment History Start -->
      <b-col class="" lg="12" md="12" sm="12" xs="12">
        <b-row class="mt-4">
           <b-col lg="12" md="12" sm="12" xs="12">
             <b-card>
               <b-card-title>
                 <b-row>
                   <b-col>
                     <h4 class="card-title mb-0 pl-0">Employment Histories</h4>
                   </b-col>
                   <b-col class="text-right">
                     <b-button v-if="has_permission('permitted')" size="sm" variant="primary" @click="openAddNewEmploymentHistoryModal()">Add New<i class="ri-add-fill"></i></b-button>
                   </b-col>
                 </b-row>
               </b-card-title>
                <b-row class="mt-3">
                  <b-col>
                    <h6 class="pl-2 text-success">Candidate Employment Histories</h6>
                  </b-col>
                </b-row>
               <b-overlay :show="loading">
                 <div class="table-wrapper table-responsive mt-2">
                   <table class="table table-hover table-bordered">
                     <thead>
                       <tr style="font-size: 12px;">
                         <th scope="col" class="text-center">SL</th>
                         <th scope="col" class="text-center">Organization Name</th>
                         <th scope="col" class="text-center">Designation</th>
                         <th scope="col" class="text-center">Start Date</th>
                         <th scope="col" class="text-center">End Date</th>
                         <th scope="col" class="text-center">Is Primary</th>
                         <!-- <th scope="col" class="text-center">Location</th>
                         <th scope="col" class="text-center">Foregin</th>
                         <th scope="col" class="text-center">Country</th>
                         <th scope="col" class="text-center">Responsibility</th> -->
                         <th scope="col" class="text-center">Action</th>
                       </tr>
                     </thead>
                     <template v-if="Object.keys(leadDetails.candidate_employment_histories || { }).length">
                     <tbody v-for="(item, index) in leadDetails.candidate_employment_histories" :key="index">
                       <tr style="font-size: 12px;">
                         <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                         <td class="text-left">
                           <span v-html="item.organization_name"></span>
                         </td>
                         <td class="text-left">
                           <span v-html="item.designation"></span>
                         </td>
                         <td class="text-left">
                           <span v-html="item.start_date"></span>
                         </td>
                         <td class="text-center">
                           <span v-if="item.end_date" v-html="item.end_date"></span>
                           <span v-if="item.still_working">Still Working</span>
                         </td>
                         <td class="text-center">
                            <span v-if="item.is_primary" class="badge badge-success badge-pill">Yes</span>
                            <span v-else class="badge badge-danger badge-pill">No</span>
                          </td>
                          <!-- <td class="text-left">
                           <span v-html="item.location"></span>
                         </td>
                         <td class="text-center">
                            <span v-if="item.is_foreign" class="badge badge-success badge-pill">Yes</span>
                            <span v-else class="badge badge-danger badge-pill">No</span>
                          </td>
                         <td class="text-left">
                           <span v-html="item.country.name"></span>
                         </td>
                         <td class="text-left">
                           <span v-html="item.responsibility"></span>
                         </td> -->
                         <td class="text-center">
                           <a v-tooltip="'Edit'" v-if="has_permission('permitted')" @click="editEmploymentHistoryDataModal(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                           <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteEmploymentHistory(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                           <a v-tooltip="'Details'" v-if="has_permission('permitted')" @click="detailsDataEmploymentHistory(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
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
               </b-overlay>
                <b-row class="mt-3">
                  <b-col>
                    <h6 class="pl-2 text-success">Dependent Employment Histories</h6>
                  </b-col>
                </b-row>
               <b-overlay :show="loading">
                 <div class="table-wrapper table-responsive mt-2 mb-0">
                   <table class="table table-hover table-bordered">
                     <thead>
                       <tr style="font-size: 12px;">
                         <th scope="col" class="text-center">SL</th>
                         <th scope="col" class="text-center">Dependent</th>
                         <th scope="col" class="text-center">Organization Name</th>
                         <th scope="col" class="text-center">Designation</th>
                         <th scope="col" class="text-center">Start Date</th>
                         <th scope="col" class="text-center">End Date</th>
                         <!-- <th scope="col" class="text-center">Still Working</th>
                         <th scope="col" class="text-center">Location</th>
                         <th scope="col" class="text-center">Foregin</th>
                         <th scope="col" class="text-center">Country</th>
                         <th scope="col" class="text-center">Responsibility</th> -->
                         <th scope="col" class="text-center">Action</th>
                       </tr>
                     </thead>
                     <template v-if="Object.keys(leadDetails.dependent_employment_histories || { }).length">
                     <tbody v-for="(item, index) in leadDetails.dependent_employment_histories" :key="index">
                       <tr style="font-size: 12px;">
                         <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                         <td class="text-left">
                           <span v-if="item.dependent" v-html="item.dependent.name"></span>
                         </td>
                         <td class="text-left">
                           <span v-html="item.organization_name"></span>
                         </td>
                         <td class="text-left">
                           <span v-html="item.designation"></span>
                         </td>
                         <td class="text-left">
                           <span v-html="item.start_date"></span>
                         </td>
                         <td class="text-center">
                           <span v-if="item.end_date" v-html="item.end_date"></span>
                           <span v-if="item.still_working">Still Working</span>
                         </td>
                         <!-- <td class="text-center">
                            <span v-if="item.still_working" class="badge badge-success badge-pill">Yes</span>
                            <span v-else class="badge badge-danger badge-pill">No</span>
                          </td>
                          <td class="text-left">
                           <span v-html="item.location"></span>
                         </td>
                         <td class="text-center">
                            <span v-if="item.is_foreign" class="badge badge-success badge-pill">Yes</span>
                            <span v-else class="badge badge-danger badge-pill">No</span>
                          </td>
                         <td class="text-left">
                           <span v-html="item.country.name"></span>
                         </td>
                         <td class="text-left">
                           <span v-html="item.responsibility"></span>
                         </td> -->
                         <td class="text-center">
                           <a v-tooltip="'Edit'" v-if="has_permission('permitted')" @click="editEmploymentHistoryDataModal(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                           <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteEmploymentHistory(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                           <a v-tooltip="'Details'" v-if="has_permission('permitted')" @click="detailsDataEmploymentHistory(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
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
               </b-overlay>
             </b-card>
           </b-col>
         </b-row>
      </b-col>
      <!-- Lead Employment history End -->

      <!-- Lead Visa History Start -->
      <b-col class="" lg="12" md="12" sm="12" xs="12">
        <b-row class="mt-4">
           <b-col lg="12" md="12" sm="12" xs="12">
             <b-card>
               <b-card-title>
                 <b-row>
                   <b-col>
                     <h4 class="card-title mb-0 pl-0">Lead Visa Histories</h4>
                   </b-col>
                   <b-col class="text-right">
                     <b-button v-if="has_permission('permitted')" size="sm" variant="primary" @click="openAddNewVisaHistoriesModal()">Add New<i class="ri-add-fill"></i></b-button>
                   </b-col>
                 </b-row>
               </b-card-title>
                <b-row class="mt-3">
                  <b-col>
                    <h6 class="pl-2 text-success">Candidate Visa Histories</h6>
                  </b-col>
                </b-row>
               <b-overlay :show="loading">
                 <div class="table-wrapper table-responsive mt-2">
                   <table class="table table-hover table-bordered">
                     <thead>
                       <tr style="font-size: 12px;">
                         <th scope="col" class="text-center">SL</th>
                         <th scope="col" class="text-center">Country</th>
                         <th scope="col" class="text-center">Visa Type</th>
                         <th scope="col" class="text-center">Start Date</th>
                         <th scope="col" class="text-center">End Date</th>
                         <th scope="col" class="text-center">Rejection Date</th>
                         <th scope="col" class="text-center">Action</th>
                       </tr>
                     </thead>
                     <template v-if="Object.keys(leadDetails.candidate_visa_histories || { }).length">
                     <tbody v-for="(item, index) in leadDetails.candidate_visa_histories" :key="index">
                       <tr style="font-size: 12px;">
                         <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                         <td class="text-left">
                           <span v-if="item.country" v-html="item.country.name"></span>
                         </td>
                         <td class="text-left">
                           <span v-if="item.visa_type" v-html="item.visa_type.name"></span>
                         </td>
                         <td class="text-left">
                           <span v-if="item.start_date" v-html="dDate(item.start_date)"></span>
                         </td>
                         <td class="text-center">
                           <span v-if="item.end_date" v-html="dDate(item.end_date)"></span>
                         </td>
                         <td class="text-left">
                           <span v-if="item.rejection_date" v-html="dDate(item.rejection_date)"></span>
                         </td>
                         <td class="text-center">
                           <a v-tooltip="'Edit'" v-if="has_permission('permitted')" @click="editVisaHistoryDataModal(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                           <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteVisaHistory(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                           <a v-tooltip="'Details'" v-if="has_permission('permitted')" @click="detailsVisaHistory(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
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
               </b-overlay>
                <b-row class="mt-3">
                  <b-col>
                    <h6 class="pl-2 text-success">Dependent Visa Histories</h6>
                  </b-col>
                </b-row>
               <b-overlay :show="loading">
                 <div class="table-wrapper table-responsive mt-2 mb-0">
                   <table class="table table-hover table-bordered">
                     <thead>
                       <tr style="font-size: 12px;">
                         <th scope="col" class="text-center">SL</th>
                         <th scope="col" class="text-center">Country</th>
                         <th scope="col" class="text-center">Dependent</th>
                         <th scope="col" class="text-center">Visa Type</th>
                         <th scope="col" class="text-center">Start Date</th>
                         <th scope="col" class="text-center">End Date</th>
                         <th scope="col" class="text-center">Rejection Date</th>
                         <th scope="col" class="text-center">Action</th>
                       </tr>
                     </thead>
                     <template v-if="Object.keys(leadDetails.dependent_visa_histories || { }).length">
                     <tbody v-for="(item, index) in leadDetails.dependent_visa_histories" :key="index">
                       <tr style="font-size: 12px;">
                         <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                         <td class="text-left">
                           <span v-if="item.dependent" v-html="item.dependent.name"></span>
                         </td>
                         <td class="text-left">
                           <span v-if="item.country" v-html="item.country.name"></span>
                         </td>
                         <td class="text-left">
                           <span v-if="item.visa_type" v-html="item.visa_type.name"></span>
                         </td>
                         <td class="text-left">
                           <span v-if="item.start_date" v-html="dDate(item.start_date)"></span>
                         </td>
                         <td class="text-center">
                           <span v-if="item.end_date" v-html="dDate(item.end_date)"></span>
                         </td>
                         <td class="text-left">
                           <span v-if="item.rejection_date" v-html="dDate(item.rejection_date)"></span>
                         </td>
                         <td class="text-center">
                           <a v-tooltip="'Edit'" v-if="has_permission('permitted')" @click="editVisaHistoryDataModal(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                           <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteVisaHistory(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                           <a v-tooltip="'Details'" v-if="has_permission('permitted')" @click="detailsVisaHistory(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
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
               </b-overlay>
             </b-card>
           </b-col>
         </b-row>
      </b-col>
      <!-- Lead Visa history End -->

      <!-- Lead Education History Start-->
      <b-col class="" lg="12" md="12" sm="12" xs="12">
        <b-row class="mt-4">
           <b-col lg="12" md="12" sm="12" xs="12">
             <b-card>
               <b-card-title>
                 <b-row>
                   <b-col>
                     <h4 class="card-title mb-0 pl-0">Education Histories</h4>
                   </b-col>
                   <b-col class="text-right">
                     <b-button v-if="has_permission('permitted')" size="sm" variant="primary" @click="openAddNewEducationHistoryModal()">Add New<i class="ri-add-fill"></i></b-button>
                   </b-col>
                 </b-row>
               </b-card-title>
                <b-row class="mt-3">
                  <b-col>
                    <h6 class="pl-2 text-success">Candidate Education Histories</h6>
                  </b-col>
                </b-row>
               <b-overlay :show="loading">
                 <div class="table-wrapper table-responsive mt-2">
                   <table class="table table-hover table-bordered">
                     <thead>
                       <tr style="font-size: 12px;">
                         <th scope="col" class="text-center">SL</th>
                         <!-- <th scope="col" class="text-center">Institute Country</th> -->
                         <th scope="col" class="text-center">Institute Name</th>
                         <th scope="col" class="text-center">Course Level</th>
                         <th scope="col" class="text-center">Course Name</th>
                         <th scope="col" class="text-center">Marks</th>
                         <th scope="col" class="text-center">Passing Year</th>
                         <th scope="col" class="text-center">Action</th>
                       </tr>
                     </thead>
                     <template v-if="Object.keys(leadDetails.candidate_education_histories || { }).length">
                     <tbody v-for="(item, index) in leadDetails.candidate_education_histories" :key="index">
                       <tr style="font-size: 12px;">
                         <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                         <!-- <td class="text-left">
                           <span v-if="item.country" v-html="item.country.name"></span>
                         </td> -->
                         <td class="text-left">
                           <span v-html="item.institute_name"></span>
                         </td>
                         <td class="text-left">
                           <span v-if="item.course_level" v-html="item.course_level.name"></span>
                         </td>
                         <td class="text-center">
                           <span v-html="item.course_name"></span>
                         </td>
                         <td class="text-center">
                           <span v-html="item.marks"></span>
                         </td>
                         <td class="text-center">
                           <span v-html="item.year_of_passing"></span>
                         </td>
                         <td class="text-center">
                           <a v-tooltip="'Edit'" v-if="has_permission('permitted')" @click="editEducationHistoryModal(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                           <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteEducationHistory(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                           <a v-tooltip="'Details'" v-if="has_permission('permitted')" @click="detailsEducationHistory(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
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
               </b-overlay>
                <b-row class="mt-3">
                  <b-col>
                    <h6 class="pl-2 text-success">Dependent Education Histories</h6>
                  </b-col>
                </b-row>
               <b-overlay :show="loading">
                 <div class="table-wrapper table-responsive mt-2 mb-0">
                   <table class="table table-hover table-bordered">
                     <thead>
                       <tr style="font-size: 12px;">
                         <th scope="col" class="text-center">SL</th>
                         <th scope="col" class="text-center">Dependent</th>
                         <!-- <th scope="col" class="text-center">Institute Country</th> -->
                         <th scope="col" class="text-center">Institute Name</th>
                         <th scope="col" class="text-center">Course Level</th>
                         <th scope="col" class="text-center">Course Name</th>
                         <th scope="col" class="text-center">Marks</th>
                         <th scope="col" class="text-center">Passing Year</th>
                         <th scope="col" class="text-center">Action</th>
                       </tr>
                     </thead>
                     <template v-if="Object.keys(leadDetails.dependent_education_histories || { }).length">
                     <tbody v-for="(item, index) in leadDetails.dependent_education_histories" :key="index">
                       <tr style="font-size: 12px;">
                         <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                         <td class="text-left">
                           <span v-if="item.dependent" v-html="item.dependent.name"></span>
                         </td>
                         <!-- <td class="text-left">
                           <span v-if="item.country" v-html="item.country.name"></span>
                         </td> -->
                         <td class="text-left">
                           <span v-html="item.institute_name"></span>
                         </td>
                         <td class="text-left">
                           <span v-if="item.course_level" v-html="item.course_level.name"></span>
                         </td>
                         <td class="text-center">
                           <span v-html="item.course_name"></span>
                         </td>
                         <td class="text-center">
                           <span v-html="item.marks"></span>
                         </td>
                         <td class="text-center">
                           <span v-html="item.year_of_passing"></span>
                         </td>
                         <td class="text-center">
                           <a v-tooltip="'Edit'" v-if="has_permission('permitted')" @click="editEducationHistoryModal(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                           <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteEducationHistory(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                           <a v-tooltip="'Details'" v-if="has_permission('permitted')" @click="detailsEducationHistory(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
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
               </b-overlay>
             </b-card>
           </b-col>
         </b-row>
      </b-col>
      <!--Lead Education History End-->
      <!--Lead Question Point Start-->
      <b-col class="" lg="12" md="12" sm="12" xs="12">
        <b-row class="mt-4">
           <b-col lg="12" md="12" sm="12" xs="12">
             <b-card>
               <b-card-title>
                 <b-row>
                   <b-col>
                     <h4 class="card-title mb-0 pl-0">Question Points</h4>
                   </b-col>
                   <b-col class="text-right">
                     <!-- <b-button v-if="has_permission('permitted')" size="sm" variant="primary" @click="openAddNewQuestionPointModal()">Add New<i class="ri-add-fill"></i></b-button> -->
                   </b-col>
                 </b-row>
               </b-card-title>
               <b-overlay :show="loading">
                 <div class="table-wrapper table-responsive mb-0">
                   <table class="table table-hover table-bordered">
                     <thead>
                       <tr style="font-size: 12px;">
                         <th scope="col" class="text-center">SL</th>
                         <th scope="col" class="text-center">Country-Service</th>
                         <th scope="col" class="text-center">Total Score</th>
                         <th scope="col" class="text-center">Entry Date</th>
                         <!-- <th scope="col" class="text-center">Action</th> -->
                       </tr>
                     </thead>
                     <template v-if="Object.keys(leadActiveServices || { }).length">
                     <tbody v-for="(item, index) in leadActiveServices" :key="index">
                       <tr style="font-size: 12px;">
                         <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                         <td class="text-center">
                           <span v-if="item.country" v-html="item.country.name"></span>-
                           <span v-if="item.service" v-html="item.service.name"></span>
                         </td>
                         <td class="text-center">
                           <span v-if="item.point_entry_date" v-html="item.total_score"></span>
                           <b-button v-else  @click="editQuestionPointModal(item)" variant="default" class="text-primary text-underlined-hover" size="sm" pill>Add Score <i class="ri-add-fill"></i></b-button>
                         </td>
                         <td class="text-center">
                           <span v-if="item.point_entry_date" v-html="dDate(item.point_entry_date)"></span>
                         </td>
                         <!-- <td class="text-center">
                           <a v-tooltip="'Edit'" v-if="has_permission('permitted')" @click="editQuestionPointModal(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                           <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteQuestionPoint(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                         </td> -->
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
               </b-overlay>
             </b-card>
           </b-col>
         </b-row>
      </b-col>
      <!--Lead Question Point End-->
      <!--Lead English Test Start-->
      <b-col class="" lg="12" md="12" sm="12" xs="12">
        <b-row class="mt-4">
           <b-col lg="12" md="12" sm="12" xs="12">
             <b-card>
               <b-card-title>
                 <b-row>
                   <b-col>
                     <h4 class="card-title mb-0 pl-0">English Tests</h4>
                   </b-col>
                   <b-col class="text-right">
                     <b-button v-if="has_permission('permitted')" size="sm" variant="primary" @click="openAddNewEnglishTestModal()">Add New<i class="ri-add-fill"></i></b-button>
                   </b-col>
                 </b-row>
               </b-card-title>
                <b-row class="mt-3">
                  <b-col>
                    <h6 class="pl-2 text-success">Candidate English Tests</h6>
                  </b-col>
                </b-row>
               <b-overlay :show="loading">
                 <div class="table-wrapper table-responsive">
                   <table class="table table-hover table-bordered">
                     <thead>
                       <tr style="font-size: 12px;">
                         <th scope="col" class="text-center">SL</th>
                         <th scope="col" class="text-center">Language Test</th>
                         <th scope="col" class="text-center">Level</th>
                         <th scope="col" class="text-center">Test Date</th>
                         <th scope="col" class="text-center">Expire Date</th>
                         <th scope="col" class="text-center">Is Primary</th>
                         <th scope="col" class="text-center">Over All Result</th>
                         <th scope="col" class="text-center">Action</th>
                       </tr>
                     </thead>
                     <template v-if="Object.keys(leadDetails.candidate_english_tests || { }).length">
                     <tbody v-for="(item, index) in leadDetails.candidate_english_tests" :key="index">
                       <tr style="font-size: 12px;">
                         <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                         <td class="text-left">
                           <span v-if="item.language_test" v-html="item.language_test.name"></span>
                         </td>
                         <td class="text-left">
                           <span v-if="item.level" v-html="item.level.name"></span>
                         </td>
                         <td class="text-left">
                           <span v-if="item.test_date" v-html="dDate(item.test_date)"></span>
                         </td>
                         <td class="text-left">
                           <span v-if="item.expire_date" v-html="dDate(item.expire_date)"></span>
                         </td>
                         <td class="text-center">
                          <span v-if="item.is_primary" class="badge badge-success badge-pill">Yes</span>
                          <span v-else class="badge badge-danger badge-pill">No</span>
                        </td>
                        <td class="text-center">
                           <span v-html="item.over_all_result"></span>
                         </td>
                         <td class="text-center">
                           <a v-tooltip="'Edit'" v-if="has_permission('permitted')" @click="editEnglishTestModal(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                           <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteEnglishTest(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
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
               </b-overlay>
                <b-row class="mt-3">
                  <b-col>
                    <h6 class="pl-2 text-success">Dependent English Tests</h6>
                  </b-col>
                </b-row>
               <b-overlay :show="loading">
                 <div class="table-wrapper table-responsive mb-0">
                   <table class="table table-hover table-bordered">
                     <thead>
                       <tr style="font-size: 12px;">
                         <th scope="col" class="text-center">SL</th>
                         <th scope="col" class="text-center">Dependent</th>
                         <th scope="col" class="text-center">Language Test</th>
                         <th scope="col" class="text-center">Level</th>
                         <th scope="col" class="text-center">Test Date</th>
                         <th scope="col" class="text-center">Expire Date</th>
                         <th scope="col" class="text-center">Is Primary</th>
                         <th scope="col" class="text-center">Over All Result</th>
                         <th scope="col" class="text-center">Action</th>
                       </tr>
                     </thead>
                     <template v-if="Object.keys(leadDetails.dependent_english_tests || { }).length">
                     <tbody v-for="(item, index) in leadDetails.dependent_english_tests" :key="index">
                       <tr style="font-size: 12px;">
                         <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                         <td class="text-left">
                           <span v-if="item.dependent" v-html="item.dependent.name"></span>
                         </td>
                         <td class="text-left">
                           <span v-if="item.language_test" v-html="item.language_test.name"></span>
                         </td>
                         <td class="text-left">
                           <span v-if="item.level" v-html="item.level.name"></span>
                         </td>
                         <td class="text-left">
                           <span v-if="item.test_date" v-html="dDate(item.test_date)"></span>
                         </td>
                         <td class="text-left">
                           <span v-if="item.expire_date" v-html="dDate(item.expire_date)"></span>
                         </td>
                         <td class="text-center">
                          <span v-if="item.is_primary" class="badge badge-success badge-pill">Yes</span>
                          <span v-else class="badge badge-danger badge-pill">No</span>
                        </td>
                        <td class="text-center">
                           <span v-html="item.over_all_result"></span>
                         </td>
                         <td class="text-center">
                           <a v-tooltip="'Edit'" v-if="has_permission('permitted')" @click="editEnglishTestModal(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                           <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteEnglishTest(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
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
               </b-overlay>
             </b-card>
           </b-col>
         </b-row>
      </b-col>
      <!--Lead English Test End-->
      <!--Lead English Test Start-->
      <b-col class="" lg="12" md="12" sm="12" xs="12">
        <b-row class="mt-4">
           <b-col lg="12" md="12" sm="12" xs="12">
             <b-card>
               <b-card-title>
                 <b-row>
                   <b-col>
                     <h4 class="card-title mb-0 pl-0">English Test Results</h4>
                   </b-col>
                   <b-col class="text-right">
                     <b-button v-if="has_permission('permitted')" size="sm" variant="primary" @click="openAddNewTestResultModal()">Add New<i class="ri-add-fill"></i></b-button>
                   </b-col>
                 </b-row>
               </b-card-title>
                <b-row class="mt-3">
                  <b-col>
                    <h6 class="pl-2 text-success">Candidate English Test Results</h6>
                  </b-col>
                </b-row>
               <b-overlay :show="loading">
                 <div class="table-wrapper table-responsive mt-2 mb-0">
                   <table class="table table-hover table-bordered">
                     <thead>
                       <tr style="font-size: 12px;">
                         <th scope="col" class="text-center">SL</th>
                         <th scope="col" class="text-center">Lead English Test</th>
                         <th scope="col" class="text-center">Child Language Test</th>
                         <th scope="col" class="text-center">Result</th>
                         <th scope="col" class="text-center">Action</th>
                       </tr>
                     </thead>
                     <template v-if="Object.keys(leadDetails.candidate_english_test_results || { }).length">
                     <tbody v-for="(item, index) in leadDetails.candidate_english_test_results" :key="index">
                       <tr style="font-size: 12px;">
                         <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                         <td class="text-left">
                           <span v-if="item.language_test" v-html="item.language_test.name"></span>
                         </td>
                         <td class="text-left">
                           <span v-if="item.child_language_test" v-html="item.child_language_test.name"></span>
                         </td>
                        <td class="text-center">
                           <span v-html="item.result"></span>
                         </td>
                         <td class="text-center">
                           <a v-tooltip="'Edit'" v-if="has_permission('permitted')" @click="editEnglishTestResultModal(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                           <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteEnglishTestResult(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
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
               </b-overlay>
                <b-row class="mt-3">
                  <b-col>
                    <h6 class="pl-2 text-success">Dependent English Test Results</h6>
                  </b-col>
                </b-row>
               <b-overlay :show="loading">
                 <div class="table-wrapper table-responsive mt-2 mb-0">
                   <table class="table table-hover table-bordered">
                     <thead>
                       <tr style="font-size: 12px;">
                         <th scope="col" class="text-center">SL</th>
                         <th scope="col" class="text-center">Dependent</th>
                         <th scope="col" class="text-center">Lead English Test</th>
                         <th scope="col" class="text-center">Child Language Test</th>
                         <th scope="col" class="text-center">Result</th>
                         <th scope="col" class="text-center">Action</th>
                       </tr>
                     </thead>
                     <template v-if="Object.keys(leadDetails.dependent_english_test_results || { }).length">
                     <tbody v-for="(item, index) in leadDetails.dependent_english_test_results" :key="index">
                       <tr style="font-size: 12px;">
                         <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                         <td class="text-left">
                           <span v-if="item.dependent" v-html="item.dependent.name"></span>
                         </td>
                         <td class="text-left">
                           <span v-if="item.language_test" v-html="item.language_test.name"></span>
                         </td>
                         <td class="text-left">
                           <span v-if="item.child_language_test" v-html="item.child_language_test.name"></span>
                         </td>
                        <td class="text-center">
                           <span v-html="item.result"></span>
                         </td>
                         <td class="text-center">
                           <a v-tooltip="'Edit'" v-if="has_permission('permitted')" @click="editEnglishTestResultModal(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                           <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteEnglishTestResult(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
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
               </b-overlay>
             </b-card>
           </b-col>
         </b-row>
      </b-col>
      <!--Lead English Test End-->
      <!--Lead Payment start-->
      <b-col class="" lg="12" md="12" sm="12" xs="12">
        <b-row class="mt-4 mb-4">
           <b-col lg="12" md="12" sm="12" xs="12">
             <b-card>
               <b-card-title>
                 <b-row>
                   <b-col>
                     <h4 class="card-title mb-0 pl-0">Lead Payments</h4>
                   </b-col>
                   <b-col class="text-right">
                     <b-button v-if="has_permission('permitted')" size="sm" variant="primary" @click="openAddLeadPaymentModal()">Add New<i class="ri-add-fill"></i></b-button>
                   </b-col>
                 </b-row>
               </b-card-title>
               <b-overlay :show="loading">
                 <div class="table-wrapper table-responsive mb-0">
                   <table class="table table-hover table-bordered">
                     <thead>
                       <tr style="font-size: 12px;">
                         <th scope="col" class="text-center">SL</th>
                         <th scope="col" class="text-center">Stage No</th>
                         <th scope="col" class="text-center">Serial</th>
                         <th scope="col" class="text-center">Amount</th>
                         <th scope="col" class="text-center">Remarks</th>
                         <th scope="col" class="text-center">Action</th>
                       </tr>
                     </thead>
                     <template v-if="Object.keys(leadDetails.lead_payments || { }).length">
                     <tbody v-for="(item, index) in leadDetails.lead_payments" :key="index">
                       <tr style="font-size: 12px;">
                         <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                         <td class="text-center">
                           <span v-if="item.stage_no" v-html="item.stage_no"></span>
                         </td>
                         <td class="text-center">
                           <span class="action-btn btn-status" style="width: 22px !important; height: 22px !important; font-size: 12px;" v-if="item.sl_no" v-html="item.sl_no"></span>
                            <a v-tooltip="'Serial Up'" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit" @click="serialUp(item)"><i class="ri-arrow-up-fill"></i></a>
                            <a v-tooltip="'Serial Down'" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit" @click="serialDown(item)"><i class="ri-arrow-down-fill"></i></a>
                         </td>
                         <td class="text-right">
                           <span v-if="item.amount" v-html="item.amount"></span>
                         </td>
                         <td class="text-left">
                           <span v-if="item.remarks" v-html="item.remarks"></span>
                         </td>
                         <td class="text-center">
                           <a v-tooltip="'Edit'" v-if="has_permission('permitted')" @click="editLeadPaymentModal(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                           <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteLeadPaymentModal(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
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
               </b-overlay>
             </b-card>
           </b-col>
         </b-row>
      </b-col>
      <!--lead Payment End-->
    </b-row>

    <!-- Lead Form -->
    <b-modal id="modal-1" ref="editModal" size="xl" title="Leads" centered :hide-footer="true">
      <Form @reloadLeadDetails="getLeadDetailsById"
      :userList="userList"
      :countryList="countryList"
      :editItem="editItem"/>
    </b-modal>

    <!-- Lead Dependent Info Form -->
    <b-modal id="modal-1" ref="addOrUpdateDependentForm" size="xl" title="Lead Dependent Info" centered :hide-footer="true">
      <DependentForm @reloadLeadDetails="getLeadDetailsById"
      :leadDetails="leadDetails"
      :userList="userList"
      :countryList="countryList"
      :editItem="editDependentItem"/>
    </b-modal>

    <!-- Lead Social Form -->
    <b-modal id="modal-1" ref="addOrUpdateSocialForm" size="xl" :title="dependentList.length ? 'Lead Social' : cn(leadDetails, 'full_name') + ' Social'" centered :hide-footer="true">
      <SocialForm @reloadLeadDetails="getLeadDetailsById"
      :leadDetails="leadDetails"
      :userList="userList"
      :countryList="countryList"
      :dependentList="dependentList"
      :editItem="editDependentItem"/>
    </b-modal>

    <!-- Lead Contact Form -->
    <b-modal id="modal-1" ref="addOrUpdateContactForm" size="lg" :title="dependentList.length ? 'Lead Contact' : cn(leadDetails, 'full_name') + ' Contact'" centered :hide-footer="true">
      <ContactForm @reloadLeadDetails="getLeadDetailsById"
      :leadDetails="leadDetails"
      :dependentList="dependentList"
      :editItem="editContactItem"/>
    </b-modal>
    <!-- Lead Emergency Contact Form -->
    <b-modal id="modal-1" ref="addOrUpdateEmergencyContact" size="lg" :title="dependentList.length ? 'Lead Emergency Contact' : cn(leadDetails, 'full_name') + ' Emergency Contact'" centered :hide-footer="true">
      <EmergencyContactForm @reloadLeadDetails="getLeadDetailsById"
      :leadDetails="leadDetails"
      :dependentList="dependentList"
      :countryList="countryList"
      :editItem="editEmergencyContactItem"/>
    </b-modal>
    <b-modal id="modal-1" ref="emergencyContactDetailsModal" size="lg" title="Lead Emergency Contact Details" centered :hide-footer="true">
      <EmergencyContactDetails
      :detailItems="emergencyDetailsItem"/>
    </b-modal>
    <!-- Lead Employment History Form -->
    <b-modal id="modal-1" ref="addOrUpdateEmploymentHistory" size="lg" :title="dependentList.length ? 'Lead Employment Histories' : cn(leadDetails, 'full_name') + ' Employment Histories'" centered :hide-footer="true">
      <EmploymentHistoryForm @reloadLeadDetails="getLeadDetailsById"
      :leadDetails="leadDetails"
      :dependentList="dependentList"
      :countryList="countryList"
      :editItem="editEmploymentHistoryItem"/>
    </b-modal>
    <b-modal id="modal-1" ref="employmentHistoryDetailsModal" size="lg" title="Lead Employment Histories Details" centered :hide-footer="true">
      <EmploymentHistoryDetails
      :DetailsItem="DetailsEmploymentHistoryItem"/>
    </b-modal>
     <!-- Lead Visa Histories Modal -->
     <b-modal id="modal-1" ref="addOrUpdateVisaHistoryModal" size="lg" :title="dependentList.length ? 'Lead Visa Histories' : cn(leadDetails, 'full_name') + ' Visa Histories'" centered :hide-footer="true">
      <VisaHistoriesForm @reloadLeadDetails="getLeadDetailsById"
      :leadDetails="leadDetails"
      :dependentList="dependentList"
      :countryList="countryList"
      :editItem="editVisaHistoryItem"/>
    </b-modal>
    <b-modal id="modal-1" ref="detailsVisaHistoryModal" size="lg" title="Lead Visa Histories Details" centered :hide-footer="true">
      <VisaHistoryDetails
      :detailItems="detailsVisaHistoryItem"/>
    </b-modal>
     <!-- Lead Education History Modal -->
     <b-modal id="modal-1" ref="addOrUpdateEducationHisModal" size="lg" :title="dependentList.length ? 'Lead Education Histories' : cn(leadDetails, 'full_name') + ' Education Histories'" centered :hide-footer="true">
      <EducationHistoryForm @reloadLeadDetails="getLeadDetailsById"
      :leadDetails="leadDetails"
      :dependentList="dependentList"
      :countryList="countryList"
      :editItem="editEducationHistoryItem"/>
    </b-modal>
    <b-modal id="modal-1" ref="educationHistoryDetailsModal" size="lg" title="Lead Education Histories Details" centered :hide-footer="true">
      <EducationHistoryDetails
      :detailItems="detailsEducationItem"/>
    </b-modal>

     <!-- Lead Services Modal -->
     <b-modal id="modal-1" ref="addOrUpdateServiceModal" size="lg" title="Lead Services" centered :hide-footer="true">
      <ServiceForm @reloadLeadDetails="getLeadDetailsById"
      :leadDetails="leadDetails"
      :editItem="editServiceItem"/>
    </b-modal>

     <!-- Lead Service Histories Modal -->
     <!-- <b-modal id="modal-1 modal-right" ref="serviceHistoryModal" size="lg" title="Lead Service Histories" :hide-footer="true">
      <ServiceHistories @reloadLeadDetails="getLeadDetailsById"
      :leadDetails="leadDetails"
      :editItem="serviceHistories"/>
    </b-modal> -->

    <b-sidebar id="sidebar-right" v-model="isSidebarOpen" sidebar-class="border-left border-secondary" title="Service Histories" right shadow backdrop>
      <div class="px-1 py-1" v-if="serviceHistories.length">
        <ServiceHistories @reloadLeadDetails="getLeadDetailsById"
        :leadDetails="leadDetails"
        :editItem="serviceHistories"/>
      </div>
    </b-sidebar>

    <!--Lead Question Point-->
    <b-modal id="modal-1" ref="addOrUpdateQuestionPointModal" size="lg" title="Lead Question Point" centered :hide-footer="true">
      <QuestionPointForm @reloadLeadDetails="getLeadDetailsById" @reloadLeadServicesById="getLeadServicesById" :leadDetails="leadDetails" :editItem="editquestionPointItem"/>
    </b-modal>
    <!--Lead English Test-->
    <b-modal id="modal-1" ref="addOrUpdateEnglishTestModal" size="lg" :title="dependentList.length ? 'Lead English Test' : cn(leadDetails, 'full_name') + ' English Test'" centered :hide-footer="true">
      <EnglishTestForm @reloadLeadDetails="getLeadDetailsById"
      :leadDetails="leadDetails"
      :editItem="editEnglishTestItem"
      :dependentList="dependentList"/>
    </b-modal>
    <!--Lead English Test Result-->
    <b-modal id="modal-1" ref="addOrUpdateEnglishTestResult" size="lg" :title="dependentList.length ? 'Lead English Test Result' : cn(leadDetails, 'full_name') + ' English Test Result'" centered :hide-footer="true">
      <EnglishTestResultForm @reloadLeadDetails="getLeadDetailsById"
      :leadDetails="leadDetails"
      :editItem="editEnglishTestResultItem"
      :dependentList="dependentList"/>
    </b-modal>
    <!--Lead Payment Modal-->
    <b-modal id="modal-1" ref="addOrUpdateLeadPaymentModal" size="lg" title="Lead Payment" centered :hide-footer="true">
      <LeadPaymentForm @reloadLeadDetails="getLeadDetailsById"
      :leadDetails="leadDetails"
      :editItem="editPaymentModalItem"/>
    </b-modal>
  </div>
</template>

<script>
import Form from './Form.vue'
import DependentForm from './components/DependentForm.vue'
import SocialForm from './components/SocialForm.vue'
import ContactForm from './components/ContactForm.vue'
import EmergencyContactForm from './components/EmergencyContactForm.vue'
import EmploymentHistoryForm from './components/EmploymentHistoryForm.vue'
import EmploymentHistoryDetails from './components/EmploymentHistoryDetails.vue'
import EmergencyContactDetails from './components/EmergencyContactDetails.vue'
import VisaHistoriesForm from './components/VisaHistoriesForm.vue'
import VisaHistoryDetails from './components/VisaHistoryDetails.vue'
import EducationHistoryForm from './components/EducationHistoryForm.vue'
import ServiceForm from './components/ServiceForm.vue'
import EducationHistoryDetails from './components/EducationHistoryDetails.vue'
import ServiceHistories from './components/ServiceHistories.vue'
import QuestionPointForm from './components/QuestionPointForm.vue'
import EnglishTestForm from './components/EnglishTestForm.vue'
import EnglishTestResultForm from './components/EnglishTestResultForm.vue'
import LeadPaymentForm from './components/LeadPaymentForm.vue'
import RestApi, { baseURL } from '@/config'

// import Treeselect from '@riophae/vue-treeselect'
// import '@riophae/vue-treeselect/dist/vue-treeselect.css'

export default {
  components: {
    Form,
    DependentForm,
    SocialForm,
    ContactForm,
    EmergencyContactForm,
    EmploymentHistoryForm,
    EmploymentHistoryDetails,
    EmergencyContactDetails,
    VisaHistoriesForm,
    VisaHistoryDetails,
    ServiceForm,
    ServiceHistories,
    EducationHistoryForm,
    ServiceForm,
    EducationHistoryDetails,
    QuestionPointForm,
    EnglishTestForm,
    EnglishTestResultForm,
    LeadPaymentForm
    // Treeselect
  },
  data () {
    return {
      fields: ['first_name', 'last_name', 'show_details'],
      items: [
        { isActive: true, age: 40, first_name: 'Dickerson', last_name: 'Macdonald' },
        { isActive: false, age: 21, first_name: 'Larsen', last_name: 'Shaw' },
        {
          isActive: false,
          age: 89,
          first_name: 'Geneva',
          last_name: 'Wilson',
          _showDetails: true
        },
        { isActive: true, age: 38, first_name: 'Jami', last_name: 'Carney' }
      ],
      search: {
        name: '',
        mobile: '',
        country_id: null,
        account_id: null
      },
      leadActiveServices: [],
      leadDetails: {},
      listData: [],
      parentList: [],
      userList: [],
      loading: false,
      editDependentItem: '',
      editSocialItem: '',
      editContactItem: '',
      editEmergencyContactItem: '',
      editItem: '',
      editEmploymentHistoryItem: '',
      DetailsEmploymentHistoryItem: '',
      emergencyDetailsItem: '',
      editVisaHistoryItem: '',
      editServiceItem: '',
      serviceHistories: '',
      detailsVisaHistoryItem: '',
      detailsVisaHistoryItem: '',
      editEducationHistoryItem: '',
      detailsEducationItem: '',
      editquestionPointItem: '',
      editEnglishTestItem: '',
      editEnglishTestResultItem: '',
      editPaymentModalItem: '',
// Details
      serviceDetails: '',
      serviceItemIndex: '',
      isSidebarOpen: false
    }
  },
  computed: {
    countryList: function () {
      return this.cn(this.$store.state.dropdowns, 'countries')
    },
    dependentList: function () {
      if (this.leadDetails.dependents) {
        return this.leadDetails.dependents.map(item => {
          item.label = item.name
          return Object.assign(item)
        })
      } else {
        return []
      }
    }
  },
  created () {
    if (this.$route.params.lead_id) {
      this.getLeadDetailsById(this.hash_id(this.$route.params.lead_id, false)[0])
      this.getLeadServicesById(this.hash_id(this.$route.params.lead_id, false)[0])
    }
  },
  mounted () {
    this.getUserList()
  },
  methods: {
    async getLeadDetailsById (leadId) {
      this.loading = true
      const params = Object.assign({ id: leadId })
      var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_lead_details_by_id', params)
      if (result.success) {
        this.leadDetails = result.data
      }
      this.loading = false
    },
    async getLeadServicesById (leadId) {
      this.loading = true
      const params = Object.assign({ id: leadId })
      var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_lead_services_by_lead_id', params)
      if (result.success) {
        this.leadActiveServices = result.data
      }
      this.loading = false
    },
    async getUserList () {
      var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_user_all_list')
      if (result.success) {
        this.userList = result.data
      }
    },
    showServiceDetails (item, index) {
      this.serviceDetails = this.serviceDetails ? '' : item.service_histories
      this.serviceItemIndex = index
    },
    openAddNewModal () {
      this.editItem = ''
      this.$refs.editModal.show()
    },
    editData (item) {
      this.editItem = JSON.stringify(item)
      this.$refs.editModal.show()
    },
    openAddNewDependentModal () {
      this.editDependentItem = ''
      this.$refs.addOrUpdateDependentForm.show()
    },
    editDependentData (item) {
      this.editDependentItem = JSON.stringify(item)
      this.$refs.addOrUpdateDependentForm.show()
    },
    deleteDependentData (item) {
      this.$swal({
        title: 'Are you sure to delete?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        focusConfirm: false
      }).then((result) => {
        if (result.isConfirmed) {
          this.deleteDependentConfirmed(item)
        }
      })
    },
    async deleteDependentConfirmed (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/delete_lead_dependent_info_data', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.getLeadDetailsById(this.leadDetails.id)
      }
    },
    openAddNewSocialModal () {
      this.editSocialItem = ''
      this.$refs.addOrUpdateSocialForm.show()
    },
    openAddNewEmergencyContactModal () {
      this.editEmergencyContactItem = ''
      this.$refs.addOrUpdateEmergencyContact.show()
    },
    editSocialData (item) {
      this.editSocialItem = JSON.stringify(item)
      this.$refs.addOrUpdateSocialForm.show()
    },
    async toggleLeadSocialActiveStatus (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/toggle_lead_social_active_status', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({ title: 'Success', message: result.message })
        this.getLeadDetailsById(this.leadDetails.id)
      }
    },
    deleteSocialData (item) {
      this.$swal({
        title: 'Are you sure to delete?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        focusConfirm: false
      }).then((result) => {
        if (result.isConfirmed) {
          this.deleteSocialConfirmed(item)
        }
      })
    },
    async deleteSocialConfirmed (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/delete_lead_social_data', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.getLeadDetailsById(this.leadDetails.id)
      }
    },
    editContactData (item) {
      this.editContactItem = JSON.stringify(item)
      this.$refs.addOrUpdateContactForm.show()
    },
    editEmerContactData (item) {
      this.editEmergencyContactItem = JSON.stringify(item)
      this.$refs.addOrUpdateEmergencyContact.show()
    },
    openAddNewContactModal () {
      this.editContactItem = ''
      this.$refs.addOrUpdateContactForm.show()
    },
    deleteContactData (item) {
      this.$swal({
        title: 'Are you sure to delete?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        focusConfirm: false
      }).then((result) => {
        if (result.isConfirmed) {
          this.deleteContactConfirmed(item)
        }
      })
    },
    async deleteContactConfirmed (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/delete_lead_contact_data', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.getLeadDetailsById(this.leadDetails.id)
      }
    },
    deleteEmerContactData (item) {
      this.$swal({
        title: 'Are you sure to delete?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        focusConfirm: false
      }).then((result) => {
        if (result.isConfirmed) {
          this.deleteEmerContactConfirmed(item)
        }
      })
    },
    async deleteEmerContactConfirmed (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/delete_lead_emergency_contact_data', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.getLeadDetailsById(this.leadDetails.id)
      }
    },
    openAddNewEmploymentHistoryModal () {
      this.editEmploymentHistoryItem = ''
      this.$refs.addOrUpdateEmploymentHistory.show()
    },
    editEmploymentHistoryDataModal (item) {
      this.editEmploymentHistoryItem = JSON.stringify(item)
      this.$refs.addOrUpdateEmploymentHistory.show()
    },
    deleteEmploymentHistory (item) {
      this.$swal({
        title: 'Are you sure to delete?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        focusConfirm: false
      }).then((result) => {
        if (result.isConfirmed) {
          this.deleteEmploymentConfirmed(item)
        }
      })
    },
    async deleteEmploymentConfirmed (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/delete_lead_employement_history_data', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.getLeadDetailsById(this.leadDetails.id)
      }
    },
    detailsDataEmploymentHistory (item) {
      this.DetailsEmploymentHistoryItem = JSON.stringify(item)
      this.$refs.employmentHistoryDetailsModal.show()
    },
    detailsVisaHistory (item) {
      this.detailsVisaHistoryItem = JSON.stringify(item)
      this.$refs.detailsVisaHistoryModal.show()
    },
    detailsDataEmergencyContact (item) {
      this.emergencyDetailsItem = JSON.stringify(item)
      this.$refs.emergencyContactDetailsModal.show()
    },
    openAddNewVisaHistoriesModal () {
      this.editVisaHistoryItem = ''
      this.$refs.addOrUpdateVisaHistoryModal.show()
    },
    editVisaHistoryDataModal (item) {
      this.editVisaHistoryItem = JSON.stringify(item)
      this.$refs.addOrUpdateVisaHistoryModal.show()
    },
    deleteVisaHistory (item) {
      this.$swal({
        title: 'Are you sure to delete?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        focusConfirm: false
      }).then((result) => {
        if (result.isConfirmed) {
          this.deleteVisaConfirmed(item)
        }
      })
    },
    async deleteVisaConfirmed (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/delete_lead_visa_history_data', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.getLeadDetailsById(this.leadDetails.id)
      }
    },
    openAddNewEducationHistoryModal () {
      this.editEducationHistoryItem = ''
      this.$refs.addOrUpdateEducationHisModal.show()
    },
    editEducationHistoryModal (item) {
      this.editEducationHistoryItem = JSON.stringify(item)
      this.$refs.addOrUpdateEducationHisModal.show()
    },
    openAddNewServiceModal () {
      this.editServiceItem = ''
      this.$refs.addOrUpdateServiceModal.show()
    },
    editServiceDataModal (item) {
      this.editServiceItem = JSON.stringify(item)
      this.$refs.addOrUpdateServiceModal.show()
    },
    openServiceHistoryModal (item) {
      this.serviceHistories = JSON.stringify(item.service_histories)
      this.isSidebarOpen = true
      // this.$refs.serviceHistoryModal.show()
    },
    deleteService (item) {
      this.$swal({
        title: 'Are you sure to delete?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        focusConfirm: false
      }).then((result) => {
        if (result.isConfirmed) {
          this.deleteServiceConfirmed(item)
        }
      })
    },
    async deleteServiceConfirmed (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/delete_lead_service_data', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.getLeadDetailsById(this.leadDetails.id)
        this.getLeadServicesById(this.leadDetails.id)
      }
    },
    async toggleLeadServiceActiveStatus (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/toggle_lead_service_active_status', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({ title: 'Success', message: result.message })
        this.getLeadDetailsById(this.leadDetails.id)
        this.getLeadServicesById(this.leadDetails.id)
      }
    },
    deleteEducationHistory (item) {
      this.$swal({
        title: 'Are you sure to delete?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        focusConfirm: false
      }).then((result) => {
        if (result.isConfirmed) {
          this.deleteEducationHisConfirmed(item)
        }
      })
    },
    async deleteEducationHisConfirmed (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/delete_education_history_data', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.getLeadDetailsById(this.leadDetails.id)
      }
    },
    detailsEducationHistory (item) {
      this.detailsEducationItem = JSON.stringify(item)
      this.$refs.educationHistoryDetailsModal.show()
    },
    openAddNewQuestionPointModal () {
      this.editquestionPointItem = ''
      this.$refs.addOrUpdateQuestionPointModal.show()
    },
    editQuestionPointModal (item) {
      this.editquestionPointItem = JSON.stringify(item)
      this.$refs.addOrUpdateQuestionPointModal.show()
    },
    deleteQuestionPoint (item) {
      this.$swal({
        title: 'Are you sure to delete?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        focusConfirm: false
      }).then((result) => {
        if (result.isConfirmed) {
          this.deleteQuestionPointConfirmed(item)
        }
      })
    },
    async deleteQuestionPointConfirmed (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/delete_question_point_data', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.getLeadDetailsById(this.leadDetails.id)
      }
    },
    openAddNewEnglishTestModal () {
      this.editEnglishTestItem= ''
      this.$refs.addOrUpdateEnglishTestModal.show()
    },
    editEnglishTestModal (item) {
      this.editEnglishTestItem = JSON.stringify(item)
      this.$refs.addOrUpdateEnglishTestModal.show()
    },
    deleteEnglishTest (item) {
      this.$swal({
        title: 'Are you sure to delete?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        focusConfirm: false
      }).then((result) => {
        if (result.isConfirmed) {
          this.deleteEnglishTestConfirmed(item)
        }
      })
    },
    async deleteEnglishTestConfirmed (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/delete_english_test_data', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.getLeadDetailsById(this.leadDetails.id)
      }
    },
    openAddNewTestResultModal () {
      this.editEnglishTestResultItem = ''
      this.$refs.addOrUpdateEnglishTestResult.show()
    },
    editEnglishTestResultModal (item) {
      this.editEnglishTestResultItem = JSON.stringify(item)
      this.$refs.addOrUpdateEnglishTestResult.show()
    },
    deleteEnglishTestResult (item) {
      this.$swal({
        title: 'Are you sure to delete?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        focusConfirm: false
      }).then((result) => {
        if (result.isConfirmed) {
          this.deleteEnglishTestResultConfirmed(item)
        }
      })
    },
    async deleteEnglishTestResultConfirmed (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/delete_lead_english_test_result_data', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.getLeadDetailsById(this.leadDetails.id)
      }
    },
    openAddLeadPaymentModal () {
      this.editPaymentModalItem = ''
      this.$refs.addOrUpdateLeadPaymentModal.show()
    },
    editLeadPaymentModal (item) {
      this.editPaymentModalItem = JSON.stringify(item)
      this.$refs.addOrUpdateLeadPaymentModal.show()
    },
    deleteLeadPaymentModal (item) {
      this.$swal({
        title: 'Are you sure to delete?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        focusConfirm: false
      }).then((result) => {
        if (result.isConfirmed) {
          this.deleteLeadPaymentConfirmed(item)
        }
      })
    },
    async deleteLeadPaymentConfirmed (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/delete_lead_payment_data', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.getLeadDetailsById(this.leadDetails.id)
      }
    },
    async serialUp (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/lead_payment_serial_up', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.getLeadDetailsById(this.leadDetails.id)
      } else {
        this.$toast.error({
          title: 'Failed',
          message: result.message
        })
      }
    },
    async serialDown (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/lead_payment_serial_down', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.getLeadDetailsById(this.leadDetails.id)
      } else {
        this.$toast.error({
          title: 'Failed',
          message: result.message
        })
      }
    }
  }
}
</script>

<style>

div#modal-right.modal {
  background: orange !important;
}

div#modal-right.modal div.modal-dialog {
    position: absolute !important;
    width: auto;
    margin: 0.5rem;
    pointer-events: none;
    right: 0 !important;
}
/* .modal-dialog {
    position: absolute;
    width: auto;
    margin: 0.5rem;
    pointer-events: none;
    right: 0;
} */

.b-sidebar-outer {
  z-index: 1072;
}
#sidebar-right {
  width: 603px !important;
  top: 50px !important;
}
</style>
