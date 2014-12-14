//
//  SignupVC.swift
//  Air_Quality
//
//  Created by Amanda Eckhardt on 12/5/14.
//  Copyright (c) 2014 Amanda Eckhardt. All rights reserved.
//

import UIKit

class SignupVC: UIViewController {
    
    @IBOutlet var txtUsername: UITextField!
    @IBOutlet var txtEmail: UITextField!
    @IBOutlet var txtDevice: UITextField!
    @IBOutlet var txtPassword: UITextField!
    @IBOutlet var txtConfirmPassword: UITextField!
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
    }
    
    @IBAction func signupTapped(sender: AnyObject) {
        var username:NSString = txtUsername.text
        var email:NSString = txtEmail.text
        var device:NSString = txtDevice.text
        var password:NSString = txtPassword.text
        var confirmPassword:NSString = txtConfirmPassword.text
        
        if ( !password.isEqualToString(confirmPassword) ) {
            var alertView:UIAlertView = UIAlertView()
            alertView.title = "Signup Failed!"
            alertView.message = "Password and confirmation don't match."
            alertView.delegate = self
            alertView.addButtonWithTitle("OK")
            alertView.show()
        }
        
        else if ( username.isEqualToString("") || password.isEqualToString("") || email.isEqualToString("") || device.isEqualToString("") || confirmPassword.isEqualToString("") ) {
            var alertView:UIAlertView = UIAlertView()
            alertView.title = "Signup Failed!"
            alertView.message = "All fields are required."
            alertView.delegate = self
            alertView.addButtonWithTitle("OK")
            alertView.show()
        }
        else {
            var post:NSString = "username=\(username)&password=\(password)&email=\(email)&device=\(device)"
            
            NSLog("PostData: %@",post);
            
            var url:NSURL = NSURL(string:"http://beta.eckama.com/air_quality/iOSSignup.php")!
            
            var postData:NSData = post.dataUsingEncoding(NSASCIIStringEncoding)!
            
            var postLength:NSString = String( postData.length )
            
            var request:NSMutableURLRequest = NSMutableURLRequest(URL: url)
            request.HTTPMethod = "POST"
            request.HTTPBody = postData
            request.setValue(postLength, forHTTPHeaderField: "Content-Length")
            request.setValue("application/x-www-form-urlencoded", forHTTPHeaderField: "Content-Type")
            request.setValue("application/json", forHTTPHeaderField: "Accept")
            
            
            var reponseError: NSError?
            var response: NSURLResponse?
            
            var urlData: NSData? = NSURLConnection.sendSynchronousRequest(request, returningResponse:&response, error:&reponseError)
            
            if ( urlData != nil ) {
                let res = response as NSHTTPURLResponse!;
                
                NSLog("Response code: %ld", res.statusCode);
                
                if (res.statusCode >= 200 && res.statusCode < 300)
                {
                    var responseData:NSString  = NSString(data:urlData!, encoding:NSUTF8StringEncoding)!
                    
                    NSLog("Response ==> %@", responseData);
                    
                    var error: NSError?
                    
                    let jsonData:NSDictionary = NSJSONSerialization.JSONObjectWithData(urlData!, options:NSJSONReadingOptions.MutableContainers , error: &error) as NSDictionary
                    
                    
                    let success:NSInteger = jsonData.valueForKey("success") as NSInteger
                    let humidity:NSString = jsonData.valueForKey("hum") as NSString
                    
                    NSLog("Humidity: %ld", humidity);
                    
                    if(success == 1)
                    {
                        NSLog("Login SUCCESS");
                        
                        var prefs:NSUserDefaults = NSUserDefaults.standardUserDefaults()
                        prefs.setObject(username, forKey: "USERNAME")
                        
                        //prefs.setObject(temperature, forKey: "TEMP")
                        prefs.setObject(humidity, forKey:"HUM")
                        //prefs.setObject(pressure, forKey:"PRESSURE");
                        //prefs.setObject(particles, forKey:"PARTICLES")
                        
                        prefs.setInteger(1, forKey: "ISLOGGEDIN")
                        prefs.synchronize()
                        
                        self.dismissViewControllerAnimated(true, completion: nil)
                    } else {
                        var error_msg:NSString
                        
                        if jsonData["error_message"] as? NSString != nil {
                            error_msg = jsonData["error_message"] as NSString
                        } else {
                            error_msg = "Unknown Error"
                        }
                        var alertView:UIAlertView = UIAlertView()
                        alertView.title = "Sign in Failed!"
                        alertView.message = error_msg
                        alertView.delegate = self
                        alertView.addButtonWithTitle("OK")
                        alertView.show()
                        
                    }
                    
                } else {
                    var alertView:UIAlertView = UIAlertView()
                    alertView.title = "Sign in Failed!"
                    alertView.message = "Connection Failed"
                    alertView.delegate = self
                    alertView.addButtonWithTitle("OK")
                    alertView.show()
                }
            } else {
                var alertView:UIAlertView = UIAlertView()
                alertView.title = "Sign in Failed!"
                alertView.message = "Connection Failure"
                if let error = reponseError {
                    alertView.message = (error.localizedDescription)
                }
                alertView.delegate = self
                alertView.addButtonWithTitle("OK")
                alertView.show()
            }
        }
    }
    
    @IBAction func gotoLogin(sender: AnyObject) {
        self.dismissViewControllerAnimated(true, completion: nil)
    }
}
