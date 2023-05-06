import React from "react";
import Clock from "react-live-clock";

class App extends React.Component {
  render() {
    return <Clock format={'HH:mm:ss'} ticking={true} timezone={this.props.timezone}/>
  }
}

export default App;
