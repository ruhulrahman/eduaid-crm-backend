<template>
    <b-container>
        <b-row>
            <b-col>
                <b-card class="mt-5">
                    <h5 class="mb-4">Form</h5>
                    <b-form>
                        <b-row>
                            <b-col sm="4">
                                <label for="input-name">Full Name</label>
                                <b-form-input
                                    id="input-name"
                                    placeholder="Jane Doe"
                                ></b-form-input>
                            </b-col>
                            <b-col sm="4">
                                <label for="input-email">Email address</label>
                                <b-form-input
                                    id="input-email"
                                    type="email"
                                    placeholder="mail@yourmail.com"
                                    :state="true"
                                ></b-form-input>
                            </b-col>
                            <b-col sm="4">
                                <label for="input-password">Password</label>
                                <b-form-input
                                    id="input-password"
                                    type="password"
                                    placeholder="Password"
                                ></b-form-input>
                            </b-col>
                            <b-col sm="4">
                                <label for="input-number">Mobile Number</label>
                                <b-form-input
                                    id="input-number"
                                    type="number"
                                    placeholder="Mobile Number"
                                ></b-form-input>
                            </b-col>
                            <b-col sm="4">
                                <b-form-group id="input-group-3" label="Food:" label-for="input-3">
                                    <b-form-select
                                        id="input-3"
                                        v-model="form.food"
                                        :options="foods"
                                        required
                                    ></b-form-select>
                                </b-form-group>
                            </b-col>
                            <b-col sm="4">
                                <label for="datepicker">Choose a date</label>
                                <b-form-datepicker b-form-datepicker id="datepicker" v-model="value"></b-form-datepicker>
                            </b-col>
                            <b-col sm="4">
                                <label for="file">Choose a file</label>
                                <b-form-file
                                    v-model="file1"
                                    placeholder="Choose a file or drop it here..."
                                    drop-placeholder="Drop file here..."
                                ></b-form-file>
                            </b-col>
                            <b-col sm="4">
                                <label for="demo-sb">Spin Button</label>
                                <b-form-spinbutton id="demo-sb" v-model="valueNumber" min="1" max="100"></b-form-spinbutton>
                            </b-col>
                            <b-col sm="4">
                                <label for="tags-pills">Enter tags</label>
                                <b-form-tags
                                    input-id="tags-pills"
                                    tag-variant="primary"
                                    tag-pills
                                    size="md"
                                    separator=" "
                                    placeholder="Enter new tags separated"
                                    class="mb-3"
                                ></b-form-tags>
                            </b-col>
                            <b-col sm="4">
                                <b-form-group label="Individual radios" v-slot="{ ariaDescribedby }">
                                    <b-form-radio v-model="selected" :aria-describedby="ariaDescribedby" name="some-radios" value="A">Option A</b-form-radio>
                                    <b-form-radio v-model="selected" :aria-describedby="ariaDescribedby" name="some-radios" value="B">Option B</b-form-radio>
                                </b-form-group>
                            </b-col>
                            <b-col sm="4">
                                <label for="textarea">Checkbox</label>
                                <b-form-checkbox
                                    id="checkbox-1"
                                    v-model="status"
                                    name="checkbox-1"
                                    value="accepted"
                                    unchecked-value="not_accepted">
                                    I accept the terms and use
                                </b-form-checkbox>
                                <b-form-checkbox
                                    id="checkbox-2"
                                    v-model="status"
                                    name="checkbox-2"
                                    value="accepted"
                                    unchecked-value="not_accepted">
                                    I accept the policy and use
                                </b-form-checkbox>
                                <b-form-checkbox
                                    id="checkbox-3"
                                    v-model="status"
                                    name="checkbox-3"
                                    value="accepted"
                                    unchecked-value="not_accepted">
                                    I accept the other terms & conditions and use
                                </b-form-checkbox>
                            </b-col>
                            <b-col sm="4">
                                <b-form-checkbox switch size="sm">Small</b-form-checkbox>
                                <b-form-checkbox switch>Default</b-form-checkbox>
                                <b-form-checkbox switch size="lg">Large</b-form-checkbox>
                            </b-col>
                            <b-col sm="12">
                                <label for="textarea">Textarea</label>
                                <b-form-textarea
                                    id="textarea"
                                    placeholder="Enter something..."
                                    rows="3"
                                ></b-form-textarea>
                            </b-col>
                        </b-row>
                    </b-form>
                </b-card>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
export default {
  data () {
    return {
      form: {
        email: '',
        name: '',
        food: null
      },
      foods: [{ text: 'Select One', value: null }, 'Carrots', 'Beans', 'Tomatoes', 'Corn'],
      value: '',
      file1: null,
      valueNumber: 50
    }
  }
}
</script>
