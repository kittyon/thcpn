<template>
  <el-dropdown>
    <span class="header-btn">
      <el-badge :value="total" class="badge">
        <i class="el-icon-bell"></i>
      </el-badge>
    </span>

    <el-dropdown-menu slot="dropdown">
      <el-dropdown-item :key="notification.id" v-for="notification in notifications">
        <notification
          :notification="notification"
          v-on:read="markAsRead(notification)"
        ></notification>
      </el-dropdown-item>
      <el-dropdown-item divided v-if="hasUnread">
        <a href="#" @click.prevent="fetchAll(null)">View All</a>
      </el-dropdown-item>
      <el-dropdown-item divided v-else>
        <p>no notifications to read</p>
      </el-dropdown-item>
    </el-dropdown-menu>
  </el-dropdown>
  <!--<li ref="dropdown" class="dropdown dropdown-notifications">
    <a @click.prevent="toggleDropdown" class="dropdown-toggle" href="#">
      <i :data-count="total" class="fa fa-bell notification-icon" :class="{ 'hide-count': !hasUnread }"></i>
    </a>

    <div class="dropdown-container">
      <div class="dropdown-toolbar">
        <div v-show="hasUnread" class="dropdown-toolbar-actions">
          <a href="#" @click.prevent="markAllRead">Mark all as read</a>
        </div>

        <h3 class="dropdown-toolbar-title">Notifications ({{ total }})</h3>
      </div>

      <ul class="dropdown-menu">
        <notification v-for="notification in notifications"
          :key="notification.id"
          :notification="notification"
          v-on:read="markAsRead(notification)"
        ></notification>

        <li v-if="!hasUnread" class="notification">
          You don't have any unread notifications.
        </li>
      </ul>

      <div v-if="hasUnread" class="dropdown-footer text-center">
        <a href="#" @click.prevent="fetchAll(null)">View All</a>
      </div>
    </div>
  </li>-->
</template>

<script>
import Notification from './notification'
import {mapState} from "vuex";
export default {
  components: { Notification },

  data: () => ({
    total: 0,
    notifications: []
  }),

  mounted () {
    this.fetch()

    if (window.Echo) {
      console.log("listen")
      this.listen()
    }

    //this.initDropdown()
  },

  computed: {
    hasUnread () {
      return this.total > 0
    },
    ...mapState(
      {
        user: state=> state.user.Current
      })
  },
  methods: {
    /**
     * Fetch notifications.
     *
     * @param {Number} limit
     */
    fetch (limit = 5) {
      axios.get('/notifications', { params: { limit }})
        .then(res => {

          var notifications = res.data.notifications || [];
          this.total = notifications.length;
          this.notifications = _.map(notifications,(n) => {
            console.log(n)
            return {
              id: n.id,
              title: n.data.title,
              body: n.data.body,
              created: n.created,
              action_url: n.data.action_url
            };
          })
        })
    },

    /**
     * Mark the given notification as read.
     *
     * @param {Object} notification
     */
    markAsRead ({ id }) {
      const index = this.notifications.findIndex(n => n.id === id)

      if (index > -1) {
        this.total--
        this.notifications.splice(index, 1)
        axios.post(`/notifications/${id}/read`)
      }
    },

    /**
     * Mark all notifications as read.
     */
    markAllRead () {
      this.total = 0
      this.notifications = []

      axios.post('/notifications/mark-all-read')
    },

    /**
     * Listen for Echo push notifications.
     */
    listen () {
      var id = this.user.id;
      window.Echo.private(`App.User.${id}`)
        .notification(notification => {
          this.total++
          this.notifications.unshift(notification)
        })
        .listen('NotificationRead', ({ notificationId }) => {
          this.total--

          const index = this.notifications.findIndex(n => n.id === notificationId)
          if (index > -1) {
            this.notifications.splice(index, 1)
          }
        })
        .listen('NotificationReadAll', () => {
          this.total = 0
          this.notifications = []
        })
    },

    /**
     * Initialize the notifications dropdown.
     */


    /**
     * Toggle the notifications dropdown.
     */

  }
}
</script>
