(function (global, factory) {
    typeof exports === 'object' &&
    typeof module !== 'undefined' ? module.exports = factory() :
        typeof define === 'function' && define.amd ? define(factory) : global._ = factory();
}(this, function () {
    'use strict';
    return function (selector) {
        const elements = document.querySelectorAll(selector);
        const bindings = {};

        let app = {
            components: {}
        };

        class Node {
            constructor(element) {
                this.element = element;
                this.type = element.type || element.nodeName.toLowerCase();
            }

            get() {
                let result = [];
                if ('select-one' === this.type) {
                    for (let i = 0; i < this.element.options.length; i++) {
                        if (this.element.options[i].selected) {
                            result.push(this.element.options[i]);
                        }
                    }
                    return result;
                }
                if ('radio' === this.type || 'checkbox' === this.type) {
                    console.log(this.element.checked);
                }
                return this.element.value;
            }

            set(value) {
                if ('select-one' === this.type) {
                    for (let i = 0; i < this.element.options.length; i++) {
                        if (this.element.options[i].value == value) {
                            this.element.options[i].selected = true;
                            return;
                        }
                    }
                    return;
                }
                if ('radio' === this.type || 'checkbox' === this.type) {
                    this.element.checked = (this.element.value == value);
                    return;
                }
                if ('text' === this.type) {
                    this.element.value = value;
                    return;
                }
                this.element.innerHTML = value;
            }
        }

        class Observable {
            constructor(model) {
                this._listeners = [];
                this._value = model.value;
            }

            notify() {
                this._listeners.forEach(listener => listener(this._value));
            }

            subscribe(listener) {
                this._listeners.push(listener);
            }

            get value() {
                return this._value;
            }

            set value(val) {
                if (val !== this._value) {
                    this._value = val;
                    this.notify();
                }
            }
        }

        const htmlToNodes = (html = '') => {
            return new DOMParser().parseFromString(html, 'text/html').body.childNodes;
        }

        app.template = (html) => {
            let nodeList = htmlToNodes(html);
            elements.forEach((element) => {
                element.parentNode.replaceChildren(...nodeList);
            });
            return app;
        }

        app.addComponent = (component) => {
            app.components[component.name] = component;
            return app;
        }

        app.bind = (model) => {
            const observable = new Observable(model);

            elements.forEach((element) => {
                observable.subscribe(() => element.value = observable.value);
                //Input
                element.onkeyup = () => observable.value = element.value;
                //Select
                element.onchange = () => observable.value = element.value;
            });
        }

        return app;
    };
}))