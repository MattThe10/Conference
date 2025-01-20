<template>
  <div>
    <p v-for="(errorMessage, index) in errorMessages" :key="index" class="warning warning-par">
      {{ errorMessage }}
    </p>
    <div class="wrapper">
      <div class="wrapper-left">
        <h1>Resetovať heslo</h1>
        <form action="" @submit.prevent="handleReset" id="basic-form">
          <div class="wrapper-inp">
            <label for="password-inp">Heslo</label>
            <input type="password" id="password-inp" class="inp" v-model="password" required />
          </div>
          <div class="wrapper-inp">
            <label for="password-confirmation-inp">Heslo znova</label>
            <input type="password" id="password-confirmation-inp" class="inp" v-model="password_confirmation" required />
          </div>
          <button type="submit" class="green-btn btn">Potvrď</button>
        </form>
      </div>
      <div class="wrapper-right">
        <img src="@/assets/office-2.jpg" class="image" alt="Conference desk with people" />
      </div>
    </div>
  </div>
</template>

<script>
import "@/styles/styles.css";
import axios from "axios";

export default {
  props: ['token', 'email'],
  data() {
    return {
      password: "",
      password_confirmation: "",
      errorMessages: [],
    };
  },
  methods: {
    async handleReset() {
      try {
        console.log("Trying to reset password...");

        await axios.post("/reset-password", {
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation,
          token: this.token
        });

        console.log("Successfull reset password");

        this.$router.push("/home");
      } catch (error) {
        console.log("Error while reset password: ", error);

        if (error.response) {
          if (error.response.data.errors) {
            this.errorMessages = Object.values(
              error.response.data.errors
            ).flat();
          } else {
            this.errorMessages = error.response.data.message || "Uknown error";
          }
        }
      }
    },
  },
};
</script>

<style>
.wrapper {
  margin-top: 5rem;
  display: flex;
  flex-direction: row;
  width: 100%;
  justify-content: center;
  align-items: center;
  height: 30rem;
}

.wrapper-left,
.wrapper-right {
  height: 100%;
  background-color: #fff;
  display: flex;
  flex-direction: column;
}

.wrapper-left {
  width: 30%;
  border-top-left-radius: 20px;
  border-bottom-left-radius: 20px;
  justify-content: center;
  align-items: center;
}

.wrapper-right {
  width: 30%;
  border-top-right-radius: 20px;
  border-bottom-right-radius: 20px;
}

h1 {
  text-transform: uppercase;
  font-size: 2.5rem;
}

#login-form {
  margin-top: 25px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 3px;
  padding: 5px;
  border-top-left-radius: 0;
  border-top-right-radius: 10px;
  border-bottom-left-radius: 10px;
  border-bottom-right-radius: 10px;
}

.wrapper-inp {
  text-align: start;
  display: flex;
  flex-direction: column;
}

.image {
  border-top-right-radius: 20px;
  border-bottom-right-radius: 20px;
  height: 100%;
}

.inp {
  width: 15rem;
  height: 1.8rem;
}

.green-btn {
  width: 100%;
  font-size: 1.5rem;
  margin-top: 15px;
  margin-bottom: 5px;
  padding: 3px;
}

p {
  font-size: 0.9rem;
}
</style>
