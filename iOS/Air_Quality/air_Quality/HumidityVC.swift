//
//  HumidityVC.swift
//  Air_Quality
//
//  Created by Amanda Eckhardt on 12/9/14.
//  Copyright (c) 2014 Amanda Eckhardt. All rights reserved.
//

import UIKit
import JBChart

class HumidityVC: UIViewController{
    //, JBBarChartViewDelegate, JBBarChartViewDataSource 
    @IBOutlet var backView: UIView!
    
    @IBOutlet var lineChart: JBBarChartView!
    
    @IBOutlet var informationLabel: UILabel!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        let prefs:NSUserDefaults = NSUserDefaults.standardUserDefaults()
        let deviceId:NSString = prefs.objectForKey("deviceId") as NSString
        NSLog("c: %@","c");
        let date:NSString = prefs.objectForKey("date") as NSString
        NSLog("d: %@",date);
        var post:NSString = "deviceId=\(deviceId)&date=\(date)"
        
        NSLog("PostData: %@",date);
        
        var url:NSURL = NSURL(string:"http://beta.eckama.com/air_quality/iOSHumidity.php")!
        
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
                
                var parseError: NSError?
                let parsedObject: AnyObject? = NSJSONSerialization.JSONObjectWithData(urlData!, options: NSJSONReadingOptions.AllowFragments,
                    error:&parseError)
                let success = 0
                if let info = parsedObject as? NSDictionary {
                    if let success = info["success"] as? Int {
                        if(success == 1)
                        {
                            var alertView:UIAlertView = UIAlertView()
                            alertView.title = "All worked!"
                            alertView.message = "working"
                            alertView.delegate = self
                            alertView.addButtonWithTitle("OK")
                            alertView.show()
                        }
                        else {
                            var alertView:UIAlertView = UIAlertView()
                            alertView.title = "Nothing worked!"
                            alertView.message = "didn't work"
                            alertView.delegate = self
                            alertView.addButtonWithTitle("OK")
                            alertView.show()
                        }
                    }
                }
                
                
            }
        }
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
    }
    
}
