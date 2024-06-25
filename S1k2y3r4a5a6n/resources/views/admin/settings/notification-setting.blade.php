
<form autocomplete="off">
            <div class="row">
                <div class="col-8">
                    <div class="card mx-2">                        
                        <div class="d-flex">
                            <input type="checkbox" wire:model="is_mail_enable" id="is_mail_enable" value="yes">
                            <label for="is_mail_enable"> &nbsp; Mail enabled? </label>
                        </div>   
                        @if($is_mail_enable) 
                        <div>
                            <div class="form-group">
                                <label for="mail_driver">Mail Driver</label>
                                <select class="form-control" id="mail_driver" wire:model="mail_driver">
                                    <option value="">Select</option>
                                    <option value="smtp">SMTP</option>
                                    <option value="mail">Mail</option>
                                    <!-- <option value="sendmail">SendMail</option>
                                    <option value="mailgun">MailGun</option>
                                    <option value="mandrill">Mandrill</option>
                                    <option value="ses">Amazon SES</option>
                                    <option value="sparkpost">Sparkpost</option>
                                    <option value="log">Log</option> -->
                                </select>
                                @error('mail_driver') <span class="error"> {{$message}}</span> @endif
                            </div> 
                            <h4 class="mt-4 mb-2">SMTP Settings:</h4>                                   
                            <div class="form-group">
                                <label for="mail_host">Mail Host</label>
                                <input type="text" name="mail_host" id="mail_host" placeholder="mail.domain.com" wire:model="mail_host">
                                @error('mail_host') <span class="error"> {{$message}}</span> @endif
                            </div> 
                            <div class="form-group">
                                <label for="mail_port">Mail Port</label>
                                <input type="text" name="mail_port" id="mail_port" placeholder="Mail Port" wire:model="mail_port">
                                @error('mail_port') <span class="error"> {{$message}}</span> @endif
                            </div> 
                            <div class="form-group">
                                <label for="mail_encryption">Mail Encryption</label>
                                <input type="text" name="mail_encryption" id="mail_encryption" placeholder="Mail Encryption" wire:model="mail_encryption">
                                @error('mail_encryption') <span class="error"> {{$message}}</span> @endif
                            </div> 
                            <div class="form-group">
                                <label for="mail_username">Mail Username</label>
                                <input type="text" name="mail_username" id="mail_username" placeholder="Mail Username" wire:model="mail_username">
                                @error('mail_username') <span class="error"> {{$message}}</span> @endif
                            </div> 
                            <div class="form-group">
                                <label for="mail_password">Mail Password</label>
                                <input type="text" name="mail_password" id="mail_password" placeholder="Mail Password" wire:model="mail_password">
                                @error('mail_password') <span class="error"> {{$message}}</span> @endif
                            </div>
                        </div>
                        @endif         
                        <div class="d-flex mt-3">
                            <input type="checkbox" wire:model="is_whatsapp_enable" id="is_whatsapp_enable" value="yes">
                            <label for="is_whatsapp_enable"> &nbsp; Whatsapp notification enabled? </label>
                        </div>   
                        @if($is_whatsapp_enable) 
                        <div>
                            <h4 class="mt-3 mb-2">Whatsapp:</h4>     
                            <hr>   
                            <div class="form-group mt-2">
                                <label for="whatsapp_token">Access Token</label>
                                <input type="text" name="whatsapp_token" id="whatsapp_token" placeholder="Permenant Access token" wire:model="whatsapp_token">
                                @error('whatsapp_token') <span class="error"> {{$message}}</span> @endif
                            </div> 
                        </div> 
                        @endif
                        <div class="d-flex mt-3">
                            <input type="checkbox" wire:model="is_sms_enable" id="is_sms_enable" value="yes">
                            <label for="is_sms_enable"> &nbsp; Sms enabled? </label>
                        </div>   
                        @if($is_sms_enable) 
                        <div>
                            <h4 class="my-2">Sms Settings:</h4>     
                            <hr>
                            <div class="form-group mt-2">
                                <label for="sms_gateway">Sms Gateway</label>
                                <select class="form-control" id="sms_gateway" wire:model="sms_gateway">
                                    <option value="">Select</option>
                                    <option value="twilio">Twilio</option>
                                </select>
                                @error('sms_gateway') <span class="error"> {{$message}}</span> @endif
                            </div> 
                            <div class="form-group">
                                <label for="twilio_number">Number</label>
                                <input type="text" name="twilio_number" id="twilio_number" placeholder="+34849900000" wire:model="twilio_number">
                                @error('twilio_number') <span class="error"> {{$message}}</span> @endif
                            </div>  
                            <div class="form-group">
                                <label for="twilio_auth_token">Auth Token</label>
                                <input type="text" name="twilio_auth_token" id="twilio_auth_token" placeholder="Auth Token" wire:model="twilio_auth_token">
                                @error('twilio_auth_token') <span class="error"> {{$message}}</span> @endif
                            </div>  
                            <div class="form-group">
                                <label for="twilio_account_sid">Account Sid</label>
                                <input type="text" name="twilio_account_sid" id="twilio_account_sid" placeholder="Account sid" wire:model="twilio_account_sid">
                                @error('twilio_account_sid') <span class="error"> {{$message}}</span> @endif
                            </div>  
                        </div>
                        @endif
                        <div class="form-group py-5">
                            <div class="float-end">
                                <a href="{{ url('admin/settings') }}?tab=notification" class="btn btn-c btn-lg" >Reset</a>
                                <button wire:click.prevent="storenotification" class="btn btn-s btn-lg">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>