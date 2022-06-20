<template>
    <footer class="main-footer">
        <strong>Copyright &copy; {{ currentDate.getFullYear() }} <inertia-link href="#">GT-Esim</inertia-link>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0
        </div>
    </footer>
</template>

<script>
    export default {
        name: "Footer",
        data() {
            return {
                currentDate: new Date,
            }
        }
    }
</script>

<style scoped>

</style>
