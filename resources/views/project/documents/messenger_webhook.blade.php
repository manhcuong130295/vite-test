<section class="align-items-center">
    <div class="align-items-center d-flex mt-3" style="cursor: pointer" onclick="showIntroduction('#introduction_messenger')">
        <span class="text-primary">Click to instructions</span><i class="ti ti-chevrons-right fs-6 introduction-icon text-primary"></i>
    </div>
    <div id="introduction_messenger" class="d-none mt-5 line-container w-100">
        <p class="fs-6 fw-bold text-center">Messenger Webhook Settings Intructions</p>
        <div class="mt-3">
            <p class="form-label">Required conditions:</p>
            <div class="fs-3">
                <ul style="list-style: disc" class="ms-3">
                    <li>You need to have a Meta Business account and an activated Messenger feature on a fan page.</li>
                    <li>If you don't have a fan page, you can create a new one (detailed instructions will be provided below).</li>
                </ul>
            </div>
        </div>
        <div class="mt-3">
            <p class="form-label">Step 1: Create a Facebook App</p>
            <div class="fs-3">
                <ul style="list-style: disc" class="ms-3">
                    <li>Visit the Facebook for Developers website <a target="blank" href="https://developers.facebook.com/">(https://developers.facebook.com/)</a> and log in with your Facebook account.</li>
                    <li>Create a new application by clicking "My Apps" and then selecting "Create App."</li>
                    <img class="my-2 mx-auto d-flex" width="60%" src="{{ asset('assets/projects/assets/images/documents/create_meta_app.jpg') }}" alt="#">
                    <li>After creating the app, you need to add a product. Select "Messenger" and set up basic configurations like the app's icon, app name, and description.</li>
                    <img width="60%" class="my-2 mx-auto d-flex" src="{{ asset('assets/projects/assets/images/documents/add_messenger_product.png') }}" alt="">
                </ul>
            </div>
        </div>
        <div class="mt-3">
            <p class="form-label">Step 2: Configure the Webhook</p>
            <div class="fs-3">
                <ul style="list-style: disc" class="ms-3">
                    <li>If you don't have a fanpage, you can create a new at here.</li>
                    <img class="my-2 mx-auto d-flex" width="60%" src="{{ asset('assets/projects/assets/images/documents/create_fanpage.png') }}" alt="#">
                    <li>In the "Access Tokens" section, create an Access Token.</li>
                    <div class="d-flex my-2 mx-auto d-flex mx-auto">
                        <img class="mx-1" width="45%" src="{{ asset('assets/projects/assets/images/documents/generate_page_token.png') }}" alt="">
                        <img class="mx-1" width="45%" src="{{ asset('assets/projects/assets/images/documents/coppy_save_token.png') }}" alt="">
                    </div>
                    <li>Register your Fanpage name, page ID & page access token on MGPT</li>
                        <img class="my-2 mx-auto d-flex" width="60%" src="{{ asset('assets/projects/assets/images/documents/setting_on_mgpt.png') }}" alt="">
                    <li>After registering on the MGPT system, you will receive a webhook URL and its corresponding verification code. Use this code in the messenger's webhook setup section.</li>
                    <li>Then, configure the Webhook to receive notifications from Messenger. In the "Webhooks" section, click on "Set Up Webhooks." This will require you to provide your server's URL and an authentication token. Enter the URL and authentication token you received from MGPT into the respective fields to activate the connection.</li>
                        <img class="my-2 mx-auto d-flex" width="60%" src="{{ asset('assets/projects/assets/images/documents/add_url_callback.png') }}" alt="">
                    <li>To verify the Webhook, Facebook will send a GET request to the URL you provide. Respond with the authentication token to verify.</li>
                    <li>After a successful connection, select the "messages, messaging_postbacks, message_deliveries, messaging_optins" field so that your MGPT can receive signals through the webhook whenever there is a message to your page.</li>
                    <img class="my-2 mx-auto d-flex" width="60%" src="{{ asset('assets/projects/assets/images/documents/add_events.png') }}" alt="">
                </ul>
            </div>
        </div>
        <div class="mt-3">
            <p class="form-label">Step 3: Send messages testing</p>
            <div class="fs-3">
                <ul style="list-style: disc" class="ms-3">
                    <li>After completing the above steps, try sending a message to your fan page to test the webhook's functionality.</li>
                </ul>
                <span class="text-danger ms-1">*</span><span class="ms-2 text-danger">Please note that because your application is currently in development mode, the webhook will only work for your account. To enable this feature for any other accounts, you'll need to submit a request to Meta and wait for approval.</span>
            </div>
        </div>
        <div class="mt-3">
            <p class="form-label">Step 4: Submit request permisions to Meta</p>
            <div class="fs-3">
                <ul style="list-style: disc" class="ms-3">
                    <li>
                        To publish the webhook feature on your page, you need to submit an approval request with the "pages_messaging" permission to Meta and wait for approval. This process may take several days. For more information, Learn more about
                        <a target="blank" href="https://developers.facebook.com/docs/graph-api/overview/access-levels">
                            Standard Access and Enhanced Access on Meta developers.
                        </a>
                    </li>
                    <img class="mt-3 mx-auto d-flex" width="60%" src="{{ asset('assets/projects/assets/images/documents/submit_pages_messaging.png') }}" alt="">
                </ul>
            </div>
            <div style="cursor: pointer" class="text-center"><i class="ti ti-chevrons-up fs-6 introduction-icon text-primary" onclick="hideIntroduction('#introduction_messenger')"></i></div>
        </div>
        <p class="fs-3 fw-semibold text-center text-primary">Thank you for reading this far.</p>
    </div>
</section>
