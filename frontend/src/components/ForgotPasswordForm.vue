<template>
  <div>
    <p v-for="(errorMessage, index) in errorMessages" :key="index" class="warning warning-par">
      {{ errorMessage }}
    </p>
    <div class="wrapper">
      <div class="wrapper-left">
        <h1 class="h1-title">Zabudnuté heslo</h1>
        <p v-if="wrongCredentials" class="warning warning-par">
          Ľutujeme, zadali ste nesprávny email alebo heslo
        </p>
        <form action="" @submit.prevent="handleSend" id="basic-form">
          <div class="wrapper-inp">
            <label for="email-inp">Email</label>
            <input type="email" id="email-inp" class="inp" v-model="email" required />
            <p class="p-forgot-password">
              <router-link to="/login">Vieš heslo?</router-link>
            </p>
          </div>
          <button type="submit" class="green-btn btn">Potvrdiť
          </button>
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
  data() {
    return {
      email: "",
      wrongCredentials: false,
      errorMessages: [],
    };
  },
  methods: {
    async handleSend() {
      try {
        console.log("Trying to send email...");

        await axios.post("/forgot-password", {
          email: this.email
        });

        console.log("Successfull send email");

        this.$router.push("/login");
      } catch (error) {
        console.log("Error while send email: ", error);

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

.h1-title {
  margin-bottom: 2rem;
}
</style>
