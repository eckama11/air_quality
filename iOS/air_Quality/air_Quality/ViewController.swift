//
//  ViewController.swift
//  Air_Quality
//
//  Created by Amanda Eckhardt on 12/9/14.
//  Copyright (c) 2014 Amanda Eckhardt. All rights reserved.
//

import UIKit

class ViewController: UIViewController {
    
    @IBOutlet var dateLabel: UILabel!
    @IBOutlet var datePicker: UIDatePicker!
    
    @IBOutlet var usernameLabel: UILabel!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        let prefs:NSUserDefaults = NSUserDefaults.standardUserDefaults()
        datePicker.addTarget(self, action: Selector("datePickerChanged:"), forControlEvents: UIControlEvents.ValueChanged)
        var dateFormatterA = NSDateFormatter()
        dateFormatterA.dateFormat = "yyyy-MM-dd"
        var strDate = dateFormatterA.stringFromDate(datePicker.date)
        dateLabel.text = strDate
        
        prefs.setObject(strDate, forKey: "date")
        NSLog("d: %@",strDate);
        // Do any additional setup after loading the view, typically from a nib.
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    override func viewDidAppear(animated: Bool) {
        super.viewDidAppear(true)
        let prefs:NSUserDefaults = NSUserDefaults.standardUserDefaults()
        let isLoggedIn:Int = prefs.integerForKey("ISLOGGEDIN") as Int
        NSLog("%i",isLoggedIn as Int)
        if (isLoggedIn != 1) {
            self.performSegueWithIdentifier("goto_login", sender: self)
        } else {
            self.usernameLabel.text = prefs.objectForKey("deviceId") as NSString
        }
    }
    @IBAction func logouTapped(sender: UIButton) {
        let prefs:NSUserDefaults = NSUserDefaults.standardUserDefaults()
        prefs.setInteger(0, forKey: "ISLOGGEDIN")
        self.performSegueWithIdentifier("goto_login", sender: self)

    }
    
    func datePickerChanged(datePicker:UIDatePicker) {
        let prefs:NSUserDefaults = NSUserDefaults.standardUserDefaults()
        var dateFormatter = NSDateFormatter()
        dateFormatter.dateFormat = "yyyy-MM-dd"
        dateFormatter.locale = NSLocale(localeIdentifier: "en_US_POSIX")
        var strDate = dateFormatter.stringFromDate(datePicker.date)
        dateLabel.text = strDate
        prefs.setObject(strDate, forKey: "date")
    }
}
