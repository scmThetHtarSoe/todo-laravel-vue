<template>
      <div class="flex justify-center items-center h-screen" id="todoApp">
      <div class="container shadow w-[500px] h-auto p-8">
        <h2 class="title text-3xl">Todo</h2>

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

        <div class="flex">
          <button
            type="button"
            id="all"
            class="flex-1 border border-gray-200 px-12 py-2"
            @click="condition = 'all'"
          >
            All
          </button>
          <button
            type="button"
            id="notdone"
            class="flex-1 border border-gray-200 px-12 py-2 mx-2"
            @click="condition = 'active'"
          >
            Active
          </button>
          <button
            type="button"
            id="done"
            class="flex-1 border border-gray-200 px-8 py-2"
            @click="condition = 'inactive'"
          >
            Completed
          </button>
        </div>

        <ul class="my-4 list-none list-group" id="list-group">
          <li
            v-for="(todo,index) in items"
            :key="todo.uni_id"
            class="bg-gray-200 text-black py-2.5 px-5 border-b-2 border-neutral-100 flex items-center"
          >
            <input
              type="checkbox"
              :id="todo.uni_id"
              :checked="todo.status = todo.status==true?true:false"
              v-model="todo.status"
              @click="checkList(todo.uni_id)"
            />

            <div v-show="todo.showEditingbox == false">
              <span
                :class="todo.status? 'line-through ml-4' : 'ml-4'"
                @dblclick="todo.showEditingbox = true;showAutofocus(index)"
                >{{ todo.text }}</span
              >
            </div>
            <input
              ref="editFocus"
              type="text"
              v-model="todo.text"
              class="w-[300px] p-2.5 border-1 border-neutral-100"
              v-show="todo.showEditingbox == true"
              v-on:blur="todo.showEditingbox = false;updateList($event,todo.uni_id)"
              @keyup.enter="todo.showEditingbox = false;updateList($event,todo.uni_id )"
            />
            <button
              @click="deleteList(todo.uni_id,index)"
              class="text-xl text-red-600 bg-white py-1 px-2 ml-auto"
            >
              &times;
            </button>
          </li>
        </ul>
        <p class="text-gray-500"><span>{{totalItem}}</span> items left</p>
        <div class="flex mt-4">
          <button
            type="button"
            id="checkAll"
            class="flex-1 border border-gray-200 px-8 py-2 mr-4"
            v-text="totalItem==0 && todos.length>0?'Uncheck All':'Check All'"
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
    </div>
</template>

<script>
import axios from 'axios';
  export default {
    data() {
    return {
      todos: [],
      newList: "",
      leftLists: [],
      condition: "all",
    }
  },
    computed: {
    totalItem() {
      return this.leftLists.filter((data) => data.status != true).length;
    },
    items() {
    switch (this.condition) {
      case "all":
        return this.todos;
      case "active":
        return this.todos.filter((data) => data.status != true);
      case "inactive":
        return this.todos.filter((data) => data.status == true);
      default:
      return this.todos;
    }
  },

  },
    methods: {
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
          uni_id: Date.now()
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
      var getThisList = this.todos.find((data)=>data.uni_id==idx);
      this.todos.filter((data) => data.uni_id == idx).status == true ? false : true;
      axios.post("http://localhost:8000/api/updateStatus/" + idx, {
        status: getThisList.status == "1"?"0":"1",
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
    showAutofocus(index) {
      this.$nextTick(() => this.$refs["editFocus"][index].focus());
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
  }
</script>

<style lang="stylus" scoped>

</style>
