<template>
  <div>
    <div class="flex justify-between">
      <h2 class="title text-3xl">Todo</h2>
      <button
        @click="logout()"
        class="bg-blue-400 px-4 py-1 text-md text-white ml-2 rounded-md"
      >
        Logout
      </button>
    </div>

    <form id="form" class="flex my-4" @submit.prevent="addList()">
      <input
        type="text"
        id="list"
        class="border border-gray-300 w-full p-2 focus:outline-blue-500"
        placeholder="Add New..."
        v-model="newList"
      />
      <button
        type="submit"
        class="bg-blue-400 px-4 py-1 text-xl text-white ml-2"
      >
        +
      </button>
    </form>
    <TodoList
      :todos="todos"
      @delete="deleteList"
      @check="checkList"
      @showfocus="showAutofocus"
      @editList="updateList"
    />
    <p class="text-gray-500">
      <span>{{ totalItem }}</span> items left
    </p>
    <div class="flex mt-4">
      <button
        type="button"
        id="checkAll"
        class="flex-1 border border-gray-200 px-8 py-2 mr-4"
        v-text="
          totalItem == 0 && todos.length > 0 ? 'Uncheck All' : 'Check All'
        "
        @click="checkAll($event)"
      ></button>
      <button
        type="button"
        id="cleardone"
        class="flex-1 border border-gray-200 px-8 py-2"
        @click="clearCompleted()"
      >
        Clear Completed
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import router from "@/router";
import TodoList from "../components/TodoList.vue";
export default {
  data() {
    return {
      todos: [],
      newList: "",
      leftLists: [],
    };
  },
  components: {
    TodoList,
  },
  computed: {
    totalItem() {
      return this.leftLists.filter((data) => data.status != true).length;
    },
  },
  methods: {
    logout() {
      let token = sessionStorage.getItem("token");
      axios
        .post("http://localhost:8000/api/logout", {
          Bearer: token,
        })
        .then((response) => {
          sessionStorage.removeItem("token");
          this.$router.push("/login");
        });
    },
    addList() {
      if (this.newList.trim() != "") {
        this.todos.push({
          uni_id: Date.now(),
          text: this.newList,
          status: 0,
          showEditingbox: 0,
        });

        axios.post("http://localhost:8000/api/createList", {
          texts: this.newList,
          uni_id: Date.now(),
        });
        this.newList = "";
      }
      this.leftLists = this.todos;
    },

    deleteList(index) {
      this.todos = this.todos.filter((data) => data.uni_id != index);
      var tasklistId = index;
      axios.delete("http://localhost:8000/api/deleteList/" + tasklistId);
      this.leftLists = this.todos;
    },

    checkList(idx) {
      var getThisList = this.todos.find((data) => data.uni_id == idx);
      this.todos.filter((data) => data.uni_id == idx).status == true
        ? false
        : true;
      axios.post("http://localhost:8000/api/updateStatus/" + idx, {
        status: getThisList.status == "1" ? "0" : "1",
      });
      this.leftLists = this.todos;
    },

    checkAll(el) {
      if (el.target.innerText == "Check All") {
        this.todos.filter((data) => (data.status = true));
        axios.post("http://localhost:8000/api/checkAll");
      } else {
        this.todos.filter((data) => (data.status = false));
        axios.post("http://localhost:8000/api/uncheckAll");
      }
    },

    clearCompleted() {
      this.todos = this.todos.filter((data) => data.status != true);
      axios.delete("http://localhost:8000/api/clearCompleted");
    },

    updateList(el, idx) {
      var val = el.target.value;
      if (el.target.value.trim() != "") {
        axios.post("http://localhost:8000/api/updateList/" + idx, {
          texts: val,
        });
      } else {
        this.todos = [];
        this.all();
      }
      this.leftLists = this.todos;
    },
    showAutofocus(index, el, tick) {
      tick(() => el[index].focus());
    },

    all() {
      const getAllLists = this.todos;
      axios.get("http://localhost:8000/api/allLists").then((response) => {
        var allLists = response.data;
        allLists.forEach(function (val) {
          getAllLists.push({
            id: val.id,
            text: val.texts,
            status: Boolean(val.status),
            uni_id: val.unquid_id,
            showEditingbox: val.showEditingbox,
          });
        });
      });
      return getAllLists;
    },
  },
  created() {
    this.todos.forEach((todo) => {
      todo.status = false;
    });
    this.all();
    this.leftLists = this.todos;
  },
  router,
};
</script>
