<template>
  <li class="notification list-group-item">
      <div class="clearfix">
        <a :href="notification.action_url" style="float: left">{{ notification.title }}</a>

        <a v-if="!notification.read_at" href="#" class="notification-mark-read" type="text" @click.prevent="markAsRead">
          <i class="fa fa-times" aria-hidden="true"></i>
        </a>
      </div>
      <div>
        <p class="notification-desc">
          {{ notification.body }}
        </p>
      </div>
      <div>
        <small class="timestamp">
          <timeago :since="notification.created" :auto-update="30"></timeago>
        </small>
      </div>
  </li>
</template>

<script>
export default {
  props: {
    notification: {
      type: Object,
      required: true
    }
  },

  methods: {
    markAsRead () {
      this.$emit('read')
    }
  }
}
</script>
<style>
  .notification-mark-read {
    float: right;
    float: right;
    color: #333;
    opacity: 0.4;

    &:hover {
      opacity: .8;
    }
  }
  .notification-title {
    font-size: 15px;
    margin-bottom: 0;
  }
  .notification-desc {
    margin-bottom: 0;
  }
</style>
