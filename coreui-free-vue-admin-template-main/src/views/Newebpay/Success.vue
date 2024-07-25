
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
            <div class="success">
                <h1>交易成功</h1>
                <p>感謝您的購買！您的訂單已經成功處理。</p>
                <router-link to="/Newebpay/Newebpay">回到首頁</router-link>
            </div>
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
  
  
  </template>
  
  <script>
  export default {
    name: 'Success',
  };
  </script>
  
  <style scoped>
  .success {
    text-align: center;
    margin-top: 50px;
  }
  </style>
  