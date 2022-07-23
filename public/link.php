<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
    <script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>
    <script>
        (async ($) => {
            // Grab a Link token to initialize Link
            const createLinkToken = async () => {
                const res = await fetch("/api/plaid/create_link_token");
                const data = await res.json();
                const linkToken = data.link_token;
                localStorage.setItem("link_token", linkToken);
                return linkToken;
            };

            // Initialize Link
            const handler = Plaid.create({
                token: await createLinkToken(),
                onSuccess: async (publicToken, metadata) => {
                    await fetch("/api/plaid/exchange_public_token", {
                        method: "POST",
                        body: JSON.stringify({
                            public_token: publicToken
                        }),
                        headers: {
                            "Content-Type": "application/json",
                        },
                    });
                    await getBalance();
                },
                onEvent: (eventName, metadata) => {
                    console.log("Event:", eventName);
                    console.log("Metadata:", metadata);
                },
                onExit: (error, metadata) => {
                    console.log(error, metadata);
                },
            });

            // Start Link when button is clicked
            const linkAccountButton = document.getElementById("link-account");
            linkAccountButton.addEventListener("click", (event) => {
                handler.open();
            });
        })(jQuery);

        // Retrieves balance information
        const getBalance = async function () {
            const response = await fetch("/api/plaid/get_trans", {
                method: "GET",
            });
            const data = await response.json();

            //Render response data
            const pre = document.getElementById("response");
            pre.textContent = JSON.stringify(data, null, 2);
            pre.style.background = "#F6F6F6";
        };

        // Check whether account is connected
        const getStatus = async function () {
            const account = await fetch("/api/plaid/is_account_connected");
            const connected = await account.json();
            if (connected.status == true) {
                getBalance();
            }
        };

        getStatus();

    </script>
    <title>Plaid Login</title>
</head>

<body>
    <button type="button" id="link-account" class="btn btn-primary btn-dark btn-lg" style="
        border: 1px solid black;
        border-radius: 5px;
        background: black;
        height: 48px;
        width: 155px;
        margin-top: 5; 
        margin-left: 10;
        color: white;
        font-size: 18px;
      ">
        <strong>Link account</strong>
    </button>
    <pre id="response" style="
        top: 60;
        margin-left: 10;
        bottom: 0;
        position: fixed;
        overflow-y: scroll;
        overflow-x: hidden;
        font-size: 14px;
      "></pre>
</body>

</html>
