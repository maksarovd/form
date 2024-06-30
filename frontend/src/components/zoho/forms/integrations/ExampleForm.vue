<template>
    <div class="form-wrapper container" style="width: 30%">
      <Form v-show="!tokenExist" @submit="initializeToken" style="border: 1px solid #ccc; padding: 20px;">
        <div class="mb-3">
          <label class="form-label"><strong>{{$t('stage_0')}}</strong></label>
        </div>
        <div class="alert alert-warning" role="alert" v-html="$t('missing_token')"></div>
        <div class="row">
          <div class="col-md-12">
            <label class="form-label">{{$t('stage_0_client_id')}}</label>
            <Field type="text" name="client_id" rules="required"  v-model="clientId" class="form-control" :placeholder="$t('stage_0_client_id_placeholder')"/>
            <ErrorMessage name="client_id" style="color: red"/>
          </div>

          <div class="col-md-12">
            <label class="form-label">{{$t('stage_0_client_secret')}}</label>
            <Field type="text" name="client_secret"  rules="required" class="form-control" :placeholder="$t('stage_0_client_secret_placeholder')"/>
            <ErrorMessage name="client_secret" style="color: red"/>
          </div>

          <div class="col-md-12">
            <label class="form-label">{{$t('stage_0_redirect_uri')}}</label>
            <Field type="text" name="redirect_uri" v-model="redirectUri" rules="required" class="form-control" :placeholder="$t('stage_0_redirect_uri_placeholder')"/>
            <ErrorMessage name="redirect_uri" style="color: red"/>
          </div>

          <div class="col-md-12">
            <label class="form-label">
              {{$t('stage_0_code')}}
              <a v-show="codelink"  :href="apiGetCode" target="_blank">{{ $t('explain_token_code_getting') }}</a>
            </label>
            <Field type="text" name="code"  rules="required" class="form-control" @focus="codelink = true" :placeholder="$t('stage_0_code_placeholder')"/>
            <ErrorMessage name="code" style="color: red"/>
          </div>

          <Field type="hidden" name="grant_type" value="authorization_code"/>

          <div class="col-12 mt-3">
            <button type="submit" class="btn btn-success">{{$t('form_add')}}</button>
          </div>
        </div>
      </Form>

      <div v-show="tokenExist" class="mb-3" >
            <label class="form-label"><strong>{{ $t('stage_1') }}</strong></label>
            <select class="form-select form-select-sm mb-3" aria-label=".form-select-lg example" v-model="selected" @change="accountExists = true">
                <option v-for="account in accounts" :value="account" :key="account.id">{{ account.Account_Name }}</option>
            </select>
            <div class="form-text">{{ $t('stage_1_notice') }}</div>
        </div>

      <Form v-show="tokenExist && !accountExists" @submit="createAccount" style="border: 1px solid #ccc; padding: 20px;">
            <div class="mb-3">
                <label class="form-label"><strong>{{$t('stage_1_create_account')}}</strong></label>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">{{$t('stage_1_name')}}</label>
                    <Field type="text" name="Account_Name" rules="required|name" class="form-control" placeholder="Account 2"/>
                    <ErrorMessage name="Account_Name" style="color: red"/>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{$t('stage_1_website')}}</label>
                    <Field type="text" name="Website" rules="required|website" class="form-control" placeholder="https://company.com/" />
                    <ErrorMessage name="Website" style="color: red"/>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{$t('stage_1_phone')}}</label>
                    <Field type="text" name="Phone" rules="required" class="form-control" placeholder="+38 (___) ___-__-__" id="phone"/>
                    <ErrorMessage name="Phone" style="color: red"/>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{$t('stage_1_city')}}</label>
                    <Field type="text" name="Billing_City" rules="required|city" class="form-control" placeholder="Ukraine"/>
                    <ErrorMessage name="Billing_City" style="color: red"/>
                </div>

                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-success">{{$t('form_submit')}}</button>
                </div>
            </div>
        </Form>

      <Form v-show="tokenExist && accountExists" @submit="createDeal" style="border: 1px solid #ccc; padding: 20px;">
            <div class="mb-3">
                <label class="form-label"><strong>{{$t('stage_2')}}</strong></label>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">{{$t('stage_2_name')}}</label>
                    <Field type="text" class="form-control" name="Deal_Name" rules="required|name" placeholder="My Deal"/>
                    <ErrorMessage name="Deal_Name" style="color: red"/>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{$t('stage_2_amount')}}</label>
                    <Field type="number" class="form-control" name="Amount" rules="amount" placeholder="1234,0000"/>
                    <ErrorMessage name="Amount" style="color: red"/>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{$t('stage_2_stage')}}</label>
                    <Field name="Stage">
                        <select v-model="selectedStage" class="form-select form-select-sm mb-3" aria-label=".form-select-lg example" name="Stage" id="Stage" >
                            <option v-for="stage in stages" :value="stage" :key="stage">{{ stage }}</option>
                        </select>
                        <div class="form-text">{{$t('stage_2_stage_notice')}}</div>
                        <ErrorMessage name="Stage" style="color: red"/>
                    </Field>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{$t('stage_2_date')}}</label>
                    <Field type="date" class="form-control" name="Closing_Date" rules="required"/>
                    <ErrorMessage name="Closing_Date" style="color: red"/>
                </div>

                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-success">{{$t('form_submit')}}</button>
                </div>
            </div>
        </Form>
    </div>
</template>


<script>
    import axios from 'axios';
    import { Form, Field, ErrorMessage, defineRule } from 'vee-validate';
    import IMask from 'imask';
    import Swal from 'sweetalert2';

    defineRule('required', value => {
      if (!value || !value.length) {
        return 'validation.required';
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

            clientId: '',
            redirectUri: '',

            codelink: false,
            tokenExist: false,
            accountExists: false,

            accounts: [],
            stages: []
          }
        },
        mounted() {
          this.initialize()
          //console.log(this)
        },
        unmounted() {
        },
        components: {
            Form,
            Field,
            ErrorMessage,
        },
        computed: {
          apiGetCode(){
            return 'https://accounts.zoho.eu/oauth/v2/auth?' +
                'client_id=' + this.clientId+
                '&response_type=code'+
                '&scope=ZohoCRM.modules.ALL&'+
                '&redirect_uri='+ this.redirectUri+
                '&access_type=offline'+
                '&prompt=consent';
          },
        },
        methods: {
            async initialize(){
                var self = this;
                try {
                    await axios.get('http://127.0.0.1/api/v2/zoho/check_token')
                    .then((response) => {
                      self.tokenExist = new Boolean(response.data).valueOf();

                      if(self.tokenExist){
                        self.getAccounts();
                        self.getStages();
                      }
                    });

                    this.mask = IMask(document.getElementById('phone'), {mask: '+{38} (000) 000-00-00'});
                } catch (error) {
                    Swal.fire({
                        title: 'Ошибка при загрузке данных: /api/v2/zoho/check_token',
                        text: error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },

            async initializeToken(formData){
              var self = this;
              try {
                await axios.post('http://127.0.0.1/api/v2/zoho/create_token', formData)
                .then((response) => {
                  self.tokenExist = new Boolean(response.data).valueOf();

                  if(self.tokenExist){
                    self.getAccounts();
                    self.getStages();
                  }
                });
              } catch (error) {
                Swal.fire({
                  title: 'Ошибка при загрузке данных: /api/v2/zoho/init_token',
                  text: error,
                  icon: 'error',
                  confirmButtonText: 'OK'
                });
              }
            },

            async createAccount(formData){
                try {
                    await axios.post('http://127.0.0.1/api/v2/zoho/store_account', formData)
                        .then(() => {
                            Swal.fire({
                                title: 'account successfully created!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        })
                        .then(this.getAccounts());


                } catch (error) {
                    Swal.fire({
                        title: 'Ошибка при загрузке данных: /api/v2/zoho/store_account',
                        text: error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },

            async createDeal(formData){
                try {
                    formData.Stage = this.selectedStage;
                    formData.Account_Name = {
                        name: this.selected.Account_Name,
                        id: this.selected.id
                    };
                    await axios.post('http://127.0.0.1/api/v2/zoho/store_deal', formData).then(() => {
                        Swal.fire({
                            title: 'deal successfully created!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    });
                } catch (error) {
                    Swal.fire({
                        title: 'Ошибка при загрузке данных: /api/v2/zoho/store_deal',
                        text: error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },

            async getAccounts(){
              try {
                const response = await axios.get('http://127.0.0.1/api/v2/zoho/get_accounts');
                this.accounts = response.data;
              } catch (error) {
                Swal.fire({
                  title: 'Ошибка при загрузке данных: /api/v2/zoho/get_accounts',
                  text: error,
                  icon: 'error',
                  confirmButtonText: 'OK'
                });
              }
            },

            async getStages(){
              try {
                const response = await axios.get('http://127.0.0.1/api/v2/zoho/get_stages');
                this.stages = response.data;
              } catch (error) {
                Swal.fire({
                  title: 'Ошибка при загрузке данных: /api/v2/zoho/get_stages',
                  text: error,
                  icon: 'error',
                  confirmButtonText: 'OK'
                });
              }
            },
        },
    }
</script>