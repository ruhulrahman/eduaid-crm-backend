<template>
  <b-form>
    <b-row>
      <b-col>
        <b-form-group
          id="division"
          label="Division Name"
          label-for="divisionInput"
        >
          <b-form-select
          id="divisionInput"
          v-model="form.division"
          :options="divisions"
          required
          ></b-form-select>
        </b-form-group>

        <b-form-group
          id="upozila"
          label="Upozila Name"
          label-for="upozilaInput">
          <b-form-select
          id="upozilaInput"
          v-model="form.upozilas"
          :options="upozilas"
          required
          ></b-form-select>
        </b-form-group>
      </b-col>
      <b-col>
        <b-form-group
          id="district"
          label="District Name"
          label-for="districtInput">
          <b-form-select
          id="districtInput"
          v-model="form.district"
          :options="districts"
          required
          ></b-form-select>
        </b-form-group>

        <b-form-group
        id="market"
        label="Market Name"
        label-for="marketInput">
            <b-form-input
            id="marketInput"
            v-model="form.market"
            type="text"
            placeholder="Enter Market"
            required
            ></b-form-input>
        </b-form-group>
      </b-col>
    </b-row>
  </b-form>
</template>
<script>
export default {
  data () {
    return {
      form: {
        division: null,
        district: null,
        upozila: null,
        market: ''
      },
      divisions: [{ text: 'Select Division', value: null }, 'Dhaka', 'Chattagram', 'Barisal', 'Sylhet'],
      districts: [{ text: 'Select District', value: null }, 'Dhaka', 'Narayanganj', 'Savar', 'Gazipur'],
      upozilas: [{ text: 'Select Upozila', value: null }, 'Bandor', 'Rupganj', 'Sonargaon', 'Araihazar'],
      value: ''
    }
  }
}
</script>
