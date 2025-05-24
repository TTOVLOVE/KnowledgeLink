import { defineStore } from 'pinia';

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null as null | Record<string, any>,
    loggedIn: false,
    loading: false,
  }),
  actions: {
    async fetchUser() {
      this.loading = true;
      try {
        const res = await fetch('https://api.moonbeaut.top/api/check_login.php', {
          credentials: 'include',
        });
        const data = await res.json();
        if (data.loggedIn) {
          // 再拉一次完整用户信息
          const userRes = await fetch('https://api.moonbeaut.top/api/profile.php', {
            credentials: 'include',
          });
          const userData = await userRes.json();
          this.user = userData.user;
          this.loggedIn = true;
        } else {
          this.user = null;
          this.loggedIn = false;
        }
      } catch (e) {
        this.user = null;
        this.loggedIn = false;
      } finally {
        this.loading = false;
      }
    },
    setUser(user: any) {
      this.user = user;
      this.loggedIn = !!user;
    },
    logout() {
      this.user = null;
      this.loggedIn = false;
    }
  }
});
