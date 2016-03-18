<div class="content">

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <div class="register-default-box">
        <h1>Register</h1>
        <!-- register form -->
        <form method="post" action="<?php echo BASE_URL; ?>admin/login/register" name="registerform">
            <!-- the user name input field uses a HTML5 pattern check -->
            <label for="login_input_username">
                Username
                <span style="display: block; font-size: 14px; color: #999;">(only letters and numbers, 2 to 64 characters)</span>
            </label>
            <input id="login_input_username" class="login_input" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required />
            
			<br /><br />
			
			<!-- the email input field uses a HTML5 email type check -->
            <label for="login_input_email">
                User's email
                <span style="display: block; font-size: 14px; color: #999;">
                    (please provide a <span style="text-decoration: underline; color: mediumvioletred;">real email address</span>,
                    you'll get a verification mail with an activation link)
                </span>
            </label>
            <input id="login_input_email" class="login_input" type="email" name="user_email" required />
            
			<br /><br />
			
			<label for="login_input_password_new">
                Password (min. 6 characters!
                <span class="login-form-password-pattern-reminder">
                    Please note: using a long sentence as a password is much much safer then something like "!c00lPa$$w0rd").
                    Have a look on
                </span>
            </label>
			<br />
            <input id="login_input_password_new" class="login_input" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />
            
			<br /><br />
			
			<label for="login_input_password_repeat">Repeat password</label>
			<br />
            <input id="login_input_password_repeat" class="login_input" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
            
			<br /><br />
			
			<input type="submit"  name="register" value="Register" />

        </form>
    </div>

</div>

