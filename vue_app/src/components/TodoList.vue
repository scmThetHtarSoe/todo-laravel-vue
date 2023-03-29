<template>
  <div>
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
        v-for="(todo, index) in items"
        :key="todo.uni_id"
        class="bg-gray-200 text-black py-2.5 px-5 border-b-2 border-neutral-100 flex items-center"
      >
        <input
          type="checkbox"
          :id="todo.uni_id"
          :checked="(todo.status = todo.status == true ? true : false)"
          v-model="todo.status"
          @click="check(todo.uni_id)"
        />

        <div v-show="todo.showEditingbox == false">
          <span
            :class="todo.status ? 'line-through ml-4' : 'ml-4'"
            @dblclick="
              todo.showEditingbox = true;
              showfocus(index);
            "
            >{{ todo.text }}</span
          >
        </div>
        <input
          ref="editFocus"
          type="text"
          v-model="todo.text"
          class="w-[300px] p-2.5 border-1 border-neutral-100"
          v-show="todo.showEditingbox == true"
          v-on:blur="
            todo.showEditingbox = false;
            editList($event, todo.uni_id);
          "
          @keyup.enter="
            todo.showEditingbox = false;
            editList($event, todo.uni_id);
          "
        />
        <button
          @click="delList(todo.uni_id)"
          class="text-xl text-red-600 bg-white py-1 px-2 ml-auto"
        >
          &times;
        </button>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      condition: "all",
    };
  },
  props: { todos: Array },
  computed: {
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
    delList(para) {
      this.$emit("delete", para);
    },
    check(para) {
      this.$emit("check", para);
    },
    showfocus(idx) {
      const getEdit = this.$refs["editFocus"];
      const getTick = this.$nextTick;
      this.$emit("showfocus", idx, getEdit, getTick);
    },
    editList(el, para) {
      this.$emit("editList", el, para);
    },
  },
};
</script>
