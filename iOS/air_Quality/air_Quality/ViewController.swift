//
//  ViewController.swift
//  Air_Quality
//
//  Created by Amanda Eckhardt on 12/3/14.
//  Copyright (c) 2014 Amanda Eckhardt. All rights reserved.
//

import UIKit

class ViewController: UIViewController {
    @IBOutlet var dateLabel: UILabel!
    
    @IBOutlet var datePicker: UIDatePicker!
    
    @IBOutlet var usernameLabel: UILabel!
    override func viewDidLoad() {
        super.viewDidLoad()
        datePicker.addTarget(self, action: Selector("datePickerChanged:"), forControlEvents: UIControlEvents.ValueChanged)
        var dateFormatterA = NSDateFormatter()
        dateFormatterA.dateStyle = NSDateFormatterStyle.ShortStyle
        var strDate = dateFormatterA.stringFromDate(datePicker.date)
        dateLabel.text = strDate
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
        if (isLoggedIn != 1) {
            self.performSegueWithIdentifier("goto_login", sender: self)
        } else {
            var myString = String(prefs.integerForKey("HUM"));
            self.usernameLabel.text = myString//prefs.objectForKey("USERNAME") as NSString
        }
    }
    
    @IBAction func logoutTapped(sender: UIButton) {
        let prefs:NSUserDefaults = NSUserDefaults.standardUserDefaults()
        prefs.setInteger(0, forKey: "ISLOGGEDIN")
        self.performSegueWithIdentifier("goto_login", sender: self)
    }
    
    func datePickerChanged(datePicker:UIDatePicker) {
        var dateFormatter = NSDateFormatter()
        
        dateFormatter.dateStyle = NSDateFormatterStyle.ShortStyle
        dateFormatter.timeStyle = NSDateFormatterStyle.ShortStyle
        
        var strDate = dateFormatter.stringFromDate(datePicker.date)
        dateLabel.text = strDate
    }
}

