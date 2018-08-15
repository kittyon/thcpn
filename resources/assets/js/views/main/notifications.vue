<template>
  <div>
    <notification-list/>
  </div>
</template>
<script>
import notificationList from "../notifications/notification-list.vue"
export default{
  components:{
    "notification-list": notificationList
  }
}
</script>
