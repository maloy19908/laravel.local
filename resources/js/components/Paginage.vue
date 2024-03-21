<template>
  <div>
    <button v-if="hasPrevious" @click="changePage(currentPage - 1)">Previous</button>
    <button v-if="hasNext" @click="changePage(currentPage + 1)">Next</button>
  </div>
</template>

<script>
export default {
  props: {
    total: {
      type: Number,
      required: true
    },
    perPage: {
      type: Number,
      default: 10
    }
  },
  computed: {
    currentPage() {
      return Math.ceil(this.total / this.perPage);
    },
    hasPrevious() {
      return this.currentPage > 1;
    },
    hasNext() {
      return this.currentPage < this.totalPages;
    }
  },
  methods: {
    changePage(page) {
      this.$emit('page-changed', page);
    }
  }
};
</script>