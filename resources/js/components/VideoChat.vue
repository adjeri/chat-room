<template>
    <div id="app" class="container">
        <h1 class="text-center">Demo Video chat</h1>
        <br />
        <div class="row">
            <div class="col-12">
                <div class="card" style="padding: 15px">
                    <div v-for="(name, userId) in friends" :key="userId">
                        <a href="#" @click.prevent="startVideoChat(userId)"
                            >Start a call with {{ name }}</a
                        >
                    </div>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-5">
                <div class="text-center">Your picture</div>
                <video ref="video-here" autoplay></video>
            </div>
            <div class="col-2 text-center">
                ⇔<br />
                Video chat
            </div>
            <div class="col-5">
                <div class="text-center">Your friend</div>
                <video ref="video-there" autoplay></video>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["me", "friends"],
    data() {
        return {
            pusher: {
                key: process.env.MIX_PUSHER_APP_KEY,
                cluster: process.env.MIX_PUSHER_APP_CLUSTER,
            },
            channel: null,
            stream: null,
            peers: {},
        };
    },
    methods: {
        startVideoChat(userId) {
            this.getPeer(userId, true);
        },
        getPeer(userId, initiator) {
            if (this.peers[userId] === undefined) {
                let peer = new Peer({
                    initiator,
                    stream: this.stream,
                    trickle: false,
                });
                peer.on("signal", (data) => {
                    this.channel.trigger("client-signal-" + userId, {
                        userId: this.me.id,
                        data: data,
                    });
                })
                    .on("stream", (stream) => {
                        const videoThere = this.$refs["video-there"];
                        videoThere.srcObject = stream;
                    })
                    .on("close", () => {
                        const peer = this.peers[userId];

                        if (peer !== undefined) {
                            peer.destroy();
                        }

                        delete this.peers[userId];
                    });

                this.peers[userId] = peer;
            }

            return this.peers[userId];
        },
    },
    mounted() {
        // console.log("props", this.me, this.friends);
        // エラー表示できます。
        // Pusher.logToConsole = true;

        // カメラ、音声にアクセス
        navigator.mediaDevices
            .getUserMedia({ video: true, audio: true })
            .then((stream) => {
                const videoHere = this.$refs["video-here"];
                videoHere.srcObject = stream;
                this.stream = stream;

                // Pusher の準備
                const pusher = new Pusher(this.pusher.key, {
                    authEndpoint: "/auth/video-chat",
                    cluster: this.pusher.cluster,
                    auth: {
                        headers: {
                            "X-CSRF-Token": document.head.querySelector(
                                'meta[name="csrf-token"]'
                            ).content,
                        },
                    },
                });
                this.channel = pusher.subscribe("private-chat");
                this.channel.bind("client-signal-" + this.me.id, (signal) => {
                    const userId = signal.userId;
                    const peer = this.getPeer(userId, false);
                    peer.signal(signal.data);
                });
            });
    },
};
</script>
