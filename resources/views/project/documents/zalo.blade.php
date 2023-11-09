<section class="align-items-center">
    <div class="align-items-center d-flex mt-3" style="cursor: pointer" onclick="showIntroduction('#introduction_zalo')">
        <span class="text-primary">Click to instructions</span><i class="ti ti-chevrons-right fs-6 introduction-icon text-primary"></i>
    </div>
    <div id="introduction_zalo" class="d-none mt-5 line-container w-100">
        <p class="fs-6 fw-bold text-center">Zalo Settings Intructions</p>
        <div class="mt-3">
            <p class="form-label">Step 1: Add new app</p>
            <div class="fs-3">
                <ul style="list-style: disc" class="ms-3">
                    <li class="my-2">Access the link <a target="blank" href="https://developers.zalo.me/">https://developers.zalo.me/ </a>and log in with your phone number or QR code.</li>
                    <li class="my-2">Click on the avatar icon in the top right corner and select "Add a new application.</li>
                    <img class="my-2 mx-auto d-flex" width="60%" src="{{ asset('assets/projects/assets/images/documents/zalo/new_app.png') }}" alt="#">
                </ul>
            </div>
        </div>
        <div class="mt-3">
            <p class="form-label">Step 2: Enter the application information.</p>
            <div class="fs-3">
                <ul style="list-style: disc" class="ms-3">
                    <img class="my-2 mx-auto d-flex" width="60%" src="{{ asset('assets/projects/assets/images/documents/zalo/create_app.png') }}" alt="#">
                </ul>
            </div>
        </div>
        <div class="mt-3">
            <p class="form-label text-danger">Step 3: In the next step, pay attention to two fields: `Application ID` corresponding to APP_ID and `Application Secret Key` corresponding to APP_SECRET.</p>
            <div class="fs-3">
                <ul style="list-style: disc" class="ms-3">
                    <img class="my-2 mx-auto d-flex" width="60%" src="{{ asset('assets/projects/assets/images/documents/zalo/client.png') }}" alt="#">
                </ul>
            </div>
        </div>
        <div class="mt-3">
            <p class="form-label">Step 4: Domain Verification</p>
            <div class="fs-3">
                <ul style="list-style: disc" class="ms-3">
                    <li class="my-2">In this step, you need to enter the domain mgpt.tomosia.com as the URL prefix <a href="javascript:void(0)">https://mgpt.tomosia.com.vn</a> and then click "Authenticate."</li>
                    <img class="mt-3 mx-auto d-flex" width="60%" src="{{ asset('assets/projects/assets/images/documents/zalo/domain.png') }}" alt="">
                    <li class="my-2">After clicking Authenticate, a popup will open. Here, you continue to select Authenticate now.</li>
                    <li class="my-2">There are two ways to do this (this process may take up to 72 hours):</li>
                    <span class="my-2">+ Choose "Upload HTML file to your website," then download the HTML file and send it to us.</span>
                    <img class="mt-3 mx-auto d-flex" width="60%" src="{{ asset('assets/projects/assets/images/documents/zalo/file_html.png') }}" alt="">
                    <span class="my-2">+ Choose Add a meta tag to your website, then copy the meta link and send it to us.</span>
                    <img class="mt-3 mx-auto d-flex" width="60%" src="{{ asset('assets/projects/assets/images/documents/zalo/meta_data.png') }}" alt="">
                </ul>
            </div>
        </div>
        <div class="mt-3">
            <p class="form-label">Step 5: Webhook</p>
            <div class="fs-3">
                <ul style="list-style: disc" class="ms-3">
                    <li class="my-2">After you send the domain verification information to us, we will send you an endpoint.</li>
                    <li class="my-2">Click Change and paste the endpoint to confirm.</li>
                    <img class="mt-3 mx-auto d-flex" width="60%" src="{{ asset('assets/projects/assets/images/documents/zalo/add_webhook.png') }}" alt="">
                </ul>
            </div>
        </div>
        <div class="mt-3">
            <p class="form-label">Step 6: Official Account</p>
            <div class="fs-3">
                <ul style="list-style: disc" class="ms-3">
                    <li class="my-2">Link your official account with your website.</li>
                    <img class="mt-3 mx-auto d-flex" width="60%" src="{{ asset('assets/projects/assets/images/documents/zalo/add_office_account.png') }}" alt="">
                    <li class="my-2">In the general settings, enter your official account callback URL as <a href="javascript:void(0)">https://mgpt.tomosia.com.vn</a> and save it. Then copy the link to request permission and paste it into your browser.</li>
                    <img class="mt-3 mx-auto d-flex" width="60%" src="{{ asset('assets/projects/assets/images/documents/zalo/callback_url.png') }}" alt="">
                    <li class="my-2">Select "Agree" and grant permission.</li>
                    <img class="mt-3 mx-auto d-flex" width="60%" src="{{ asset('assets/projects/assets/images/documents/zalo/confirm_token.png') }}" alt="">
                    <li class="my-2">Our website will open with an `oa_id` like this. You can copy this `oa_id` and send it to us.</li>
                    <img class="mt-3 mx-auto d-flex" width="60%" src="{{ asset('assets/projects/assets/images/documents/zalo/token_oa_id.png') }}" alt="">
                </ul>
            </div>
        </div>
        <div class="mt-3">
            <p class="form-label">Step 7: Office account</p>
            <div class="fs-3">
                <ul style="list-style: disc" class="ms-3">
                    <li class="text-danger my-2">In the official account's details, copy the ID and name and fill in the form with the fields `Official Account ID` and `Official Account Name`</li>
                    <img class="mt-3 mx-auto d-flex" width="60%" src="{{ asset('assets/projects/assets/images/documents/zalo/oa_id.png') }}" alt="">
                </ul>
            </div>
        </div>
        <div style="cursor: pointer" class="text-center"><i class="ti ti-chevrons-up fs-6 introduction-icon text-primary" onclick="hideIntroduction('#introduction_zalo')"></i></div>
        <p class="fs-3 fw-semibold text-center text-primary">Thank you for reading this far.</p>
    </div>
</section>
