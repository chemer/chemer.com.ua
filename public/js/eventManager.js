if (typeof eventManager === "undefined") {
    var eventManager = new EventManager();
}

function EventManager() {
    this.triggerCollection = {};
}

EventManager.prototype.addTrigger = function(name, subject, eventType){
    this.triggerCollection[name] = {
        subject: subject,
        eventType: eventType
    };
};
    
EventManager.prototype.execTrigger = function(name){
    if (typeof this.triggerCollection[name] === "undefined") {
        return;
    }
    var subject = this.triggerCollection[name]["subject"],
        eventType = this.triggerCollection[name]["eventType"];

    delete this.triggerCollection[name];
    
    subject.trigger(eventType);
};
