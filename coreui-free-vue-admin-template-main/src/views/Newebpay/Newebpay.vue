<template>
  <CRow>
    <CCol :xs="12">
      <CCard class="mb-4">
        <CCardHeader>
          <strong>Newebpay</strong> <small>Basic example</small>
        </CCardHeader>
        <CCardBody>
          <p class="text-body-secondary small">
            測試金流串接之功能運作
          </p>
          <CInputGroup class="mb-3">
            <CInputGroupText id="inputGroup-sizing-default">商品名稱</CInputGroupText>
            <CFormInput v-model="postData.itemName" aria-label="Item Name" aria-describedby="inputGroup-sizing-default"/>
          </CInputGroup>
          <CForm>
            <div class="mb-3">
              <CFormLabel for="exampleFormControlInput1">電子郵件</CFormLabel>
              <CFormInput v-model="postData.email" type="email" id="exampleFormControlInput1" placeholder="name@example.com"/>
            </div>
          </CForm>
          <CInputGroup class="mb-3">
            <CInputGroupText>交易金額</CInputGroupText>
            <CFormInput v-model="postData.amount" aria-label="Amount (to the nearest dollar)" />
            <CInputGroupText>元</CInputGroupText>
          </CInputGroup>
          <CInputGroup class="mb-3">
            <CInputGroupText id="inputGroup-sizing-default">卡號</CInputGroupText>
            <CFormInput v-model="postData.cardNumber" aria-label="Card Number" aria-describedby="inputGroup-sizing-default"/>
          </CInputGroup>
          <CInputGroup class="mb-3">
            <CInputGroupText id="inputGroup-sizing-default">到期日(yymm)</CInputGroupText>
            <CFormInput v-model="postData.cardExpiry" aria-label="Card Expiry" aria-describedby="inputGroup-sizing-default"/>
          </CInputGroup>
          <CInputGroup class="mb-3">
            <CInputGroupText id="inputGroup-sizing-default">後三碼</CInputGroupText>
            <CFormInput v-model="postData.cvv" aria-label="CVV" aria-describedby="inputGroup-sizing-default"/>
          </CInputGroup>
          <CButton class="d-grid gap-2 col-1 mx-auto" type="submit" color="primary" @click="sendRequest">提交訂單</CButton>
        </CCardBody>
      </CCard>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      postData: {
        url: 'https://ccore.newebpay.com/API/CreditCard',
        merchantId: 'MS153174180',
        key: '8KmLVQfhzP6nV2PJbYFeF2ApMpKVCgSs',
        iv: 'Cm28dNYLkJI7t8kP',
        version: '1.1',
        timestamp: Math.floor(Date.now() / 1000),
        itemName: '',
        email: '',
        amount: '',
        cardNumber: '',
        cardExpiry: '',
        cvv: ''
      }
    };
  },
  methods: {
    sendRequest() {
      axios.post('creditcard_api.php', this.postData)
        .then(response => {
          console.log(response.data);
          if (response.data.status === 'success') {
            this.$router.push('/Newebpay/success');
          } else {
            this.$router.push('/Newebpay/failure');
          }
        })
        .catch(error => {
          console.error(error);
          this.$router.push('/Newebpay/failure');
        });
    }
  }
};
</script>

<style scoped>
.mb-4 {
  margin-bottom: 1.5rem;
}
</style>
