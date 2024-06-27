<template>
    <div class="alert alert-warning" role="alert" v-show="alertMissingToken">
        Zoho Token is Missing. Please use <strong> <a href="http://127.0.0.1/storage/ApiDocumentation.md" target="_blank">visit a documentation</a></strong> endpoint to fix this.
    </div>

    <div class="form-wrapper container" style="width: 30%">

        <div class="mb-3" v-show="showAccountSelect">
            <label class="form-label"><strong>Stage 1. Select Account to make deal</strong></label>
            <select class="form-select form-select-sm mb-3" aria-label=".form-select-lg example" v-model="selected" @change="toggleDealForm">
                <option v-for="account in accounts" :value="account" :key="account.id">{{ account.Account_Name }}</option>
            </select>
            <div class="form-text">Select one of created zoho accounts</div>
        </div>

        <Form id="createNewAccount" v-show="showAccountForm" @submit="createAccount" style="border: 1px solid #ccc; padding: 20px;">
            <div class="mb-3">
                <label class="form-label"><strong>Or Create new account</strong></label>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <Field type="text" name="Account_Name" rules="required|name" class="form-control" placeholder="Account 2"/>
                    <ErrorMessage name="Account_Name" style="color: red"/>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Website</label>
                    <Field type="text" name="Website" rules="required|website" class="form-control" placeholder="https://company.com/" />
                    <ErrorMessage name="Website" style="color: red"/>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <Field type="text" name="Phone" rules="required" class="form-control" placeholder="+38 (___) ___-__-__" id="phone"/>
                    <ErrorMessage name="Phone" style="color: red"/>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Billing City</label>
                    <Field type="text" name="Billing_City" rules="required|city" class="form-control" placeholder="Ukraine"/>
                    <ErrorMessage name="Billing_City" style="color: red"/>
                </div>

                <div class="col-12 m-3">
                    <button type="submit" class="btn btn-primary">Create new account</button>
                </div>
            </div>
        </Form>

        <Form id="createNewDeal" v-show="showDealForm" @submit="createDeal" style="border: 1px solid #ccc; padding: 20px;">
            <div class="mb-3">
                <label class="form-label"><strong>Stage 2. Create new deal</strong></label>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Deal Name</label>
                    <Field type="text" class="form-control" name="Deal_Name" rules="required|name" placeholder="My Deal"/>
                    <ErrorMessage name="Deal_Name" style="color: red"/>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Amount</label>
                    <Field type="number" class="form-control" name="Amount" rules="amount" placeholder="1234,0000"/>
                    <ErrorMessage name="Amount" style="color: red"/>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Stage</label>
                    <Field name="Stage">
                        <select v-model="selectedStage" class="form-select form-select-sm mb-3" aria-label=".form-select-lg example" name="Stage" id="Stage" >
                            <option v-for="stage in stages" :value="stage" :key="stage">{{ stage }}</option>
                        </select>
                        <div class="form-text">Select one of deal stages</div>
                        <ErrorMessage name="Stage" style="color: red"/>
                    </Field>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Closing Date</label>
                    <Field type="date" class="form-control" name="Closing_Date" rules="required"/>
                    <ErrorMessage name="Closing_Date" style="color: red"/>
                </div>

                <div class="col-12 m-3">
                    <button type="submit" class="btn btn-primary">Create new deal</button>
                </div>
            </div>
        </Form>
    </div>
</template>


<script>
    import axios from 'axios';
    import { Form, Field, ErrorMessage, defineRule } from 'vee-validate';
    import IMask from 'imask';

    defineRule('required', value => {
        if (!value || !value.length) {
            return 'This field is required';
        }
        return true;
    });

    defineRule('name', value => {
        const usernamePattern = /^[A-Za-zа-яА-ЯёЁ0-9 ]*$/;

        if (!usernamePattern.test(value)) {
            return 'field has invalid format';
        }
        return true;
    });

    defineRule('phone', value => {
        const usernamePattern = /^[0-9]*$/i;

        if (!usernamePattern.test(value)) {
            return 'field has invalid format';
        }
        return true;
    });

    defineRule('amount', value => {
        const usernamePattern = /^-?\d+(\.\d+)?$/;

        if (!usernamePattern.test(value)) {
            return 'field has invalid format';
        }
        return true;
    });

    defineRule('city', value => {
        const usernamePattern = /^[A-Za-zа-яА-ЯёЁ]*$/;

        if (!usernamePattern.test(value)) {
            return 'field has invalid format';
        }
        return true;
    });

    defineRule('website', value => {
        const usernamePattern = /^(https?:\/\/)?([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}(:\d+)?(\/[^\s]*)?$/;

        if (!usernamePattern.test(value)) {
            return 'field has invalid format';
        }
        return true;
    });


    export default {
        data() {
            return {
                selected: '',
                selectedStage: '',

                showDealForm: false,
                showAccountForm: true,
                showAccountSelect: true,
                alertMissingToken: false,

                accounts: [],
                stages: []
            }
        },
        mounted() {
            this.initialize();
        },
        components: {
            Form,
            Field,
            ErrorMessage,
        },
        methods: {
            async initialize(){
                var self = this;
                try {

                    await axios.get('http://127.0.0.1/api/v2/zoho/check_token').then(function(response){

                        let tokenExist = new Boolean(response.data).valueOf();

                        self.alertMissingToken = !tokenExist;
                        self.showAccountForm = tokenExist;
                        self.showAccountSelect = tokenExist;
                    });


                    this.getAccounts();
                    this.getStages();
                    this.mask = IMask(document.getElementById('phone'), {mask: '+{38} (000) 000-00-00'});
                } catch (error) {
                    console.error('Ошибка при загрузке данных:', error);
                }
            },

            async getAccounts(){
                try {
                    const response = await axios.get('http://127.0.0.1/api/v2/zoho/get_accounts');
                    this.accounts = response.data;
                } catch (error) {
                    console.error('Ошибка при загрузке данных:', error);
                }
            },

            async getStages(){
                try {
                    const response = await axios.get('http://127.0.0.1/api/v2/zoho/get_stages');
                    this.stages = response.data;
                } catch (error) {
                    console.error('Ошибка при загрузке данных:', error);
                }
            },

            async createAccount(formData){
                try {
                    await axios.post('http://127.0.0.1/api/v2/zoho/store_account', formData)
                        .then(this.getAccounts());

                    alert('account successfully created')
                } catch (error) {
                    console.error('Ошибка при загрузке данных:', error);
                }
            },

            async createDeal(formData){
                try {
                    formData.Stage = this.selectedStage;
                    formData.Account_Name = {
                        name: this.selected.Account_Name,
                        id: this.selected.id
                    };
                    const response = await axios.post('http://127.0.0.1/api/v2/zoho/store_deal', formData);

                    alert(response.statusText);
                } catch (error) {
                    console.error('Ошибка при загрузке данных:', error);
                }
            },

            toggleDealForm(){
                this.showDealForm = true;
                this.showAccountForm = false;
            },
        },
    }
</script>