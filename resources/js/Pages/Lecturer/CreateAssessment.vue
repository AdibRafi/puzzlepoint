<template>
    <div v-for="items in 4" class="m-4">
        <div class="card w-96 bg-base-100 shadow-xl my-2">
            <div class="card-body">
                <h2 class="card-title">Question {{ items }} </h2>
                <input type="text" placeholder="Type Question here"
                       class="input input-bordered input-primary w-full max-w-xs"/>
                <div class="card-actions flex flex-col">
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <input type="radio" name="radio-10" class="mr-2 radio radio-primary" checked/>
                            <input type="text" placeholder="Type answer here"
                                   class="input input-bordered input-primary input-sm w-full max-w-xs"/>
                        </label>
                        <label class="label cursor-pointer">
                            <input type="radio" name="radio-10" class="mr-2 radio radio-primary" checked/>
                            <input type="text" placeholder="Type answer here"
                                   class="input input-bordered input-primary input-sm w-full max-w-xs"/>
                        </label>
                        <label class="label cursor-pointer">
                            <input type="radio" name="radio-10" class="mr-2 radio radio-primary" checked/>
                            <input type="text" placeholder="Type answer here"
                                   class="input input-bordered input-primary input-sm w-full max-w-xs"/>
                        </label>
                        <label class="label cursor-pointer">
                            <input type="radio" name="radio-10" class="mr-2 radio radio-primary" checked/>
                            <input type="text" placeholder="Type answer here"
                                   class="input input-bordered input-primary input-sm w-full max-w-xs"/>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card w-96 bg-base-100 shadow-xl my-2 mb-4">
        <div class="card-body">
            <div class="card-actions justify-center my-2">
                <button class="btn btn-primary">Create Assessment</button>
            </div>
        </div>
    </div>
</template>

<script setup>
</script>

<style>

</style>
