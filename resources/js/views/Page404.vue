<template>
    <section class="section-404">
        <div class="container__view">
            <div class="content">
                <h1>404</h1>
                <span class="stroke"/>

                <router-link to="/">
                    <button class="btn btn-primary">На главную</button>
                </router-link>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "Page404"
}
</script>

<style scoped lang="scss">
@import "@/assets/styles/variables.scss";

.container__view {
    width: 100%;
    height: 100vh;
    margin-bottom: -50px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

    .content {
        width: 70%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 1;

        h1 {
            color: $greyTextColor;
            font-size: 200px;
            line-height: 0;
            margin: 0 auto;
        }

        .stroke {
            display: block;
            box-shadow: 0 2px 10px 0 rgba(0, 0, 0, 0.3);
            border-radius: 5px;
            height: 3px;
            background: $greyTextColor;
            transition: all .3s;
            margin-top: 120px;
        }

        .btn-primary {
            width: 100%;
            padding: 20px;
        }
    }
}
</style>
