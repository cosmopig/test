<template>
  <CRow>
    <CCol :xs="12">
      <CCard class="mb-4">
        <CCardHeader>
          <strong>Vue Accordion</strong>
        </CCardHeader>
        <CCardBody>
          <p class="text-body-secondary small">
            Click the accordions below to expand/collapse the accordion content.
          </p>
          <div class="failure">
            <h1>交易失敗</h1>
            <p>很抱歉，您的交易失敗。請重新嘗試。</p>
            <router-link to="/Newebpay/Newebpay">回到首頁</router-link>
          </div>
        </CCardBody>
      </CCard>
    </CCol>
  </CRow>

</template>

  <script>
  export default {
    name: 'Failure',
  };
  </script>
  
  <style scoped>
  .failure {
    text-align: center;
    margin-top: 50px;
  }
  </style>
  